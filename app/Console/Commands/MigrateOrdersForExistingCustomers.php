<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\InvoiceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MigrateOrdersForExistingCustomers extends Command
{
    protected $signature = 'migrate:existing-customer-orders';
    protected $description = 'Migrate recurring parent orders (day-based) for existing customers';

    private $migratedOrders = 0;
    private $errorCount = 0;

    public function handle()
    {
        $this->info("Starting migration of recurring parent orders...");

        try {
            $customerIds = Customer::pluck('id')->toArray();

            if (empty($customerIds)) {
                $this->error('No customers found in the database.');
                return 1;
            }

            $this->info("Found " . count($customerIds) . " customers in the database");

            DB::beginTransaction();

            // Migrate recurring parent orders (day-based only)
            $this->migrateRecurringParentOrders($customerIds);

            DB::commit();

            // Display summary
            $this->newLine(2);
            $this->info("✅ Migration completed successfully!");
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Customers Found', count($customerIds)],
                    ['Recurring Parent Orders Migrated', $this->migratedOrders],
                    ['Errors', $this->errorCount],
                ]
            );

            Log::info('Recurring parent orders migration completed', [
                'customers_count' => count($customerIds),
                'orders_migrated' => $this->migratedOrders,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
            $this->error('Line: ' . $e->getLine());
            $this->error('File: ' . $e->getFile());

            Log::error('Recurring parent orders migration failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return 1;
        }

        return 0;
    }

    private function migrateRecurringParentOrders($customerIds)
    {
        // Get recurring parent orders with orderDay set
        $oldOrders = DB::connection('old_mysql')
            ->table('orders')
            ->whereIn('customerID', $customerIds)
            ->where('cancelled', 0)
            ->where('recurring', 1)
            ->where('recurringInstance', 0)
            ->whereNotNull('orderDay')
            ->where('orderDay', '!=', '')
            ->get();

        $this->info("Found {$oldOrders->count()} recurring parent orders (day-based) to migrate.");

        if ($oldOrders->isEmpty()) {
            return;
        }

        $bar = $this->output->createProgressBar(count($oldOrders));
        $bar->start();

        foreach ($oldOrders as $oldOrder) {
            $this->createRecurringParentOrder($oldOrder);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function createRecurringParentOrder($oldOrder)
    {
        try {
            $customer = Customer::find($oldOrder->customerID);

            if (!$customer) {
                $this->errorCount++;
                Log::warning("Customer not found for order {$oldOrder->orderID}, customerID: {$oldOrder->customerID}");
                return;
            }

            // Get supplier_id from old database
            $oldCustomer = DB::connection('old_mysql')
                ->table('login')
                ->where('customerID', $oldOrder->customerID)
                ->first();

            $supplierId = $oldCustomer->supply_location ?? null;

            // Determine status
            $status = Order::VALID;
            if ($oldOrder->cancelled == 1) {
                $status = Order::CANCELLED;
            } elseif ($oldOrder->recurSkip == 1) {
                $status = Order::SKIP;
            }

            // Get next delivery date based on orderDay
            if (empty($oldOrder->orderDay)) {
                $this->errorCount++;
                Log::warning("No orderDay for recurring order {$oldOrder->orderID}");
                return;
            }

            $deliveryDate = $this->getNextOccurrenceOfDay($oldOrder->orderDay);

            // Calculate costs
            $iceAmount = (float)$oldOrder->count;
            $boxAmount = $this->extractBoxAmount($oldOrder->notes);

            $iceUnitPrice = 1.95;
            $boxUnitPrice = 30.00;

            $iceTotal = $iceAmount * $iceUnitPrice;
            $boxTotal = $boxAmount * $boxUnitPrice;
            $subTotal = $iceTotal + $boxTotal;

            $deliveryCost = $oldOrder->delivery == 1 ? 25.00 : 0.00;
            $tax = ($iceTotal * 0.05) + ($boxTotal * 0.12) + ($deliveryCost * 0.05);
            $totalCost = $subTotal + $deliveryCost + $tax;

            // Create recurring parent order
            $order = Order::create([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'email' => $customer->email ?? '',
                'phone' => $customer->phone ?? '',
                'amount_of_ice' => $iceAmount,
                'amount_of_boxes' => $boxAmount,
                'origin' => 'manual',
                'recurring' => Order::RECURRING,
                'location_name' => '',
                'address' => $customer->address ?? '',
                'unit' => '',
                'city' => $customer->city ?? '',
                'province' => $customer->province ?? null,
                'postal_code' => $customer->postal_code ?? '',
                'country' => $customer->country ?? 'Canada',
                'pickup_delivery' => $oldOrder->delivery == 1 ? 'delivery' : 'pickup',
                'status' => $status,
                'hazmat' => 0,
                'delivery_date' => $deliveryDate,
                'notes' => $oldOrder->notes ?? '',
                'sub_total' => $subTotal,
                'delivery_cost' => $deliveryCost,
                'tax' => $tax,
                'total_cost' => $totalCost,
                'push' => 1,
                'supplier_id' => $supplierId,
                'invoice_id' => null,
            ]);

            // Create order items
            if ($iceAmount > 0) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => 1,
                    'amount_of_items' => $iceAmount,
                    'unit_price' => $iceUnitPrice,
                    'total_price' => $iceTotal,
                ]);
            }

            if ($boxAmount > 0) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => 2,
                    'amount_of_items' => $boxAmount,
                    'unit_price' => $boxUnitPrice,
                    'total_price' => $boxTotal,
                ]);
            }

            // Create invoice
            $invoiceService = new InvoiceService();
            $invoiceService->createInvoiceForOrder($order);

            $this->migratedOrders++;

            Log::info('Migrated recurring parent order', [
                'old_order_id' => $oldOrder->orderID,
                'new_order_id' => $order->id,
                'customer_id' => $customer->id,
                'order_day' => $oldOrder->orderDay,
                'delivery_date' => $deliveryDate->toDateString(),
                'supplier_id' => $supplierId,
            ]);

        } catch (\Exception $e) {
            $this->errorCount++;
            Log::error('Failed to migrate recurring parent order', [
                'old_order_id' => $oldOrder->orderID,
                'customer_id' => $oldOrder->customerID,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Get next occurrence of a specific day of week
     */
    private function getNextOccurrenceOfDay($dayName)
    {
        $daysMap = [
            'Monday' => Carbon::MONDAY,
            'Tuesday' => Carbon::TUESDAY,
            'Wednesday' => Carbon::WEDNESDAY,
            'Thursday' => Carbon::THURSDAY,
            'Friday' => Carbon::FRIDAY,
            'Saturday' => Carbon::SATURDAY,
            'Sunday' => Carbon::SUNDAY,
        ];

        $dayConstant = $daysMap[$dayName] ?? Carbon::MONDAY;

        // Get next occurrence of this day (not including today)
        return Carbon::now()->next($dayConstant);
    }

    private function extractBoxAmount($notes)
    {
        if (preg_match('/(\d+)\s*box(es)?/i', $notes, $matches)) {
            return (int)$matches[1];
        }
        return 0;
    }
}
