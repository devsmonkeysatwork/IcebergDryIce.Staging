<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RecurringOrder;
use App\Services\InvoiceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MigrateOrdersForExistingCustomers extends Command
{
    protected $signature = 'migrate:existing-customer-orders';
    protected $description = 'Migrate all future orders for customers currently in the database';

    private $orderIdMap = [];
    private $migratedOrders = 0;
    private $migratedRecurringInstances = 0;
    private $errorCount = 0;

    public function handle()
    {
        $this->info("Starting migration of future orders for existing customers...");

        try {
            $customerIds = Customer::pluck('id')->toArray();

            if (empty($customerIds)) {
                $this->error('No customers found in the database.');
                return 1;
            }

            $this->info("Found " . count($customerIds) . " customers in the database");

            DB::beginTransaction();

            $currentTimestamp = time();

            // Step 1: Migrate future one-time orders
            $this->info("\nStep 1: Migrating future one-time orders...");
            $this->migrateFutureOneTimeOrders($customerIds, $currentTimestamp);

            // Step 2: Migrate recurring parent orders (by day)
            $this->info("\nStep 2: Migrating recurring parent orders...");
            $this->migrateFutureRecurringParents($customerIds);

            // Step 3: Migrate future recurring instances
            $this->info("\nStep 3: Migrating future recurring instances...");
            $this->migrateFutureRecurringInstances($customerIds, $currentTimestamp);

            DB::commit();

            // Display summary
            $this->newLine(2);
            $this->info("✅ Migration completed successfully!");
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Customers Found', count($customerIds)],
                    ['Orders Migrated', $this->migratedOrders],
                    ['Recurring Instances Migrated', $this->migratedRecurringInstances],
                    ['Errors', $this->errorCount],
                ]
            );

            Log::info('Existing customer orders migration completed', [
                'customers_count' => count($customerIds),
                'orders_migrated' => $this->migratedOrders,
                'recurring_instances_migrated' => $this->migratedRecurringInstances,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
            $this->error('Line: ' . $e->getLine());
            $this->error('File: ' . $e->getFile());

            Log::error('Existing customer orders migration failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return 1;
        }

        return 0;
    }

    private function migrateFutureOneTimeOrders($customerIds, $currentTimestamp)
    {
        $oldOrders = DB::connection('old_mysql')
            ->table('orders')
            ->whereIn('customerID', $customerIds)
            ->where('recurring', 0)
            ->where('recurringInstance', 0)
            ->where('cancelled', 0)
            ->where('orderDate', '>', $currentTimestamp)
            ->get();

        $this->info("Found {$oldOrders->count()} future one-time orders to migrate.");

        if ($oldOrders->isEmpty()) {
            return;
        }

        $bar = $this->output->createProgressBar(count($oldOrders));
        $bar->start();

        foreach ($oldOrders as $oldOrder) {
            $this->createOrderFromOld($oldOrder, Order::NON_RECURRING);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function migrateFutureRecurringParents($customerIds)
    {
        // For recurring orders, use orderDay instead of orderDate
        $oldOrders = DB::connection('old_mysql')
            ->table('orders')
            ->whereIn('customerID', $customerIds)
            ->where('cancelled', 0)
            ->where('recurring', 1)
            ->where('recurringInstance', 0)
            ->whereNotNull('orderDay')
            ->where('orderDay', '!=', '')
            ->get();

        $this->info("Found {$oldOrders->count()} recurring parent orders to migrate.");

        if ($oldOrders->isEmpty()) {
            return;
        }

        $bar = $this->output->createProgressBar(count($oldOrders));
        $bar->start();

        foreach ($oldOrders as $oldOrder) {
            $this->createOrderFromOld($oldOrder, Order::RECURRING);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function migrateFutureRecurringInstances($customerIds, $currentTimestamp)
    {
        $oldInstances = DB::connection('old_mysql')
            ->table('orders')
            ->whereIn('customerID', $customerIds)
            ->where('cancelled', 0)
            ->where('recurringInstance', 1)
            ->where('orderDate', '>', $currentTimestamp)
            ->get();

        $this->info("Found {$oldInstances->count()} future recurring instances to migrate.");

        if ($oldInstances->isEmpty()) {
            return;
        }

        $bar = $this->output->createProgressBar(count($oldInstances));
        $bar->start();

        foreach ($oldInstances as $oldInstance) {
            $this->createRecurringInstanceFromOld($oldInstance);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function createOrderFromOld($oldOrder, $recurringType)
    {
        try {
            $customer = Customer::find($oldOrder->customerID);

            if (!$customer) {
                $this->errorCount++;
                Log::warning("Customer not found for order {$oldOrder->orderID}, customerID: {$oldOrder->customerID}");
                return;
            }

            // Determine status
            $status = Order::VALID;
            if ($oldOrder->cancelled == 1) {
                $status = Order::CANCELLED;
            } elseif ($oldOrder->recurSkip == 1) {
                $status = Order::SKIP;
            }

            // Get delivery date based on order type
            if ($recurringType === Order::RECURRING && !empty($oldOrder->orderDay)) {
                // For recurring orders, use the day name to get next occurrence
                $deliveryDate = $this->getNextOccurrenceOfDay($oldOrder->orderDay);
            } elseif (!empty($oldOrder->orderDate) && $oldOrder->orderDate > 0) {
                // For non-recurring orders, use the timestamp
                $deliveryDate = Carbon::createFromTimestamp($oldOrder->orderDate);
            } else {
                $this->errorCount++;
                Log::warning("Invalid date for order {$oldOrder->orderID}");
                return;
            }

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

            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'email' => $customer->email ?? '',
                'phone' => $customer->phone ?? '',
                'amount_of_ice' => $iceAmount,
                'amount_of_boxes' => $boxAmount,
                'origin' => 'manual',
                'recurring' => $recurringType,
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
                'supplier_id' => null,
                'invoice_id' => null,
            ]);

            // Map old order ID to new order ID for recurring instances
            $this->orderIdMap[$oldOrder->orderID] = $order->id;

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

        } catch (\Exception $e) {
            $this->errorCount++;
            Log::error('Failed to migrate order', [
                'old_order_id' => $oldOrder->orderID,
                'customer_id' => $oldOrder->customerID,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function createRecurringInstanceFromOld($oldInstance)
    {
        try {
            // Extract parent order ID
            $parentOrderId = $this->extractParentOrderId($oldInstance->notes);
            if (!$parentOrderId) {
                $this->errorCount++;
                Log::warning("Could not find parent order ID for instance {$oldInstance->orderID}");
                return;
            }

            // Get new parent order ID from the mapping
            $newParentOrderId = $this->orderIdMap[$parentOrderId] ?? null;

            if (!$newParentOrderId) {
                $this->errorCount++;
                Log::warning("Parent order not found for instance {$oldInstance->orderID} (parent: {$parentOrderId})");
                return;
            }

            // Determine status
            $status = RecurringOrder::OPEN;
            if ($oldInstance->cancelled == 1) {
                $status = RecurringOrder::CANCELLED;
            }

            // Get delivery date from timestamp
            if (!empty($oldInstance->orderDate) && $oldInstance->orderDate > 0) {
                $deliveryDate = Carbon::createFromTimestamp($oldInstance->orderDate);
            } else {
                $this->errorCount++;
                Log::warning("Invalid date for recurring instance {$oldInstance->orderID}");
                return;
            }

            // Create recurring order
            $recurringOrder = RecurringOrder::create([
                'order_id' => $newParentOrderId,
                'scheduled_delivery_date' => $deliveryDate,
                'status' => $status,
                'recurring_payment_status' => 'pending',
            ]);

            // Create invoice
            $invoiceService = new InvoiceService();
            $invoiceService->createInvoiceForRecurringOrder($recurringOrder);

            $this->migratedRecurringInstances++;

        } catch (\Exception $e) {
            $this->errorCount++;
            Log::error('Failed to migrate recurring instance', [
                'old_instance_id' => $oldInstance->orderID,
                'customer_id' => $oldInstance->customerID,
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

    private function extractParentOrderId($notes)
    {
        if (preg_match('/Instance of Order#\s*(\d+)/i', $notes, $matches)) {
            return (int)$matches[1];
        }
        return null;
    }

    private function extractBoxAmount($notes)
    {
        if (preg_match('/(\d+)\s*box(es)?/i', $notes, $matches)) {
            return (int)$matches[1];
        }
        return 0;
    }
}
