<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RecurringOrder;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MigrateOldOrders extends Command
{
    protected $signature = 'migrate:old-orders';
    protected $description = 'Migrate orders from old PHP database to new Laravel structure';

    // Map old customer IDs to new customer IDs
    private $customerIdMap = [];

    // Map old order IDs to new order IDs
    private $orderIdMap = [];

    public function handle()
    {
        $this->info('Starting migration...');

        try {
            DB::beginTransaction();

            // Step 1: Migrate Customers
            $this->info('Step 1: Migrating customers...');
            $this->migrateCustomers();

//             Step 2: Migrate One-Time Orders
            $this->info('Step 2: Migrating one-time orders...');
            $this->migrateOneTimeOrders();

            // Step 3: Migrate Recurring Parent Orders
            $this->info('Step 3: Migrating recurring parent orders...');
            $this->migrateRecurringParentOrders();

            // Step 4: Migrate Recurring Instances
            $this->info('Step 4: Migrating recurring instances...');
            $this->migrateRecurringInstances();

            DB::commit();
            $this->info('Migration completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
            $this->error('Line: ' . $e->getLine());
            $this->error('File: ' . $e->getFile());
            return 1;
        }

        return 0;
    }

    private function migrateCustomers()
    {
        $oldCustomers = DB::connection('old_mysql')
            ->table('login')
            ->get();

        $bar = $this->output->createProgressBar(count($oldCustomers));
        $bar->start();
        $i = 0;
        foreach ($oldCustomers as $oldCustomer) {
            // Check if customer already exists by email
            $customer = Customer::where('email', $oldCustomer->email)->first();
            $newEmail = $oldCustomer->email;
            if ($oldCustomer->email && $customer) {
                $newEmail = $oldCustomer->login.'-'.$oldCustomer->email;
            }else{
                $newEmail = $oldCustomer->login.$i.'@test.com';
            }

            $customerAddress = DB::table(env('OLD_DB_DATABASE', 'old_database') . '.customer_addresses')
                ->where('customer_id', $oldCustomer->customerID)
                ->first();

            $customer = Customer::create([
                'name' => $oldCustomer->login,
                'email' => Str::lower(Str::replace(' ', '-', $newEmail)),
                'password' => bcrypt($oldCustomer->password),
                'phone' => $customerAddress->phone ?? '',
                'address' => $customerAddress->address ?? '',
                'city' => $customerAddress->city ?? '',
                'postal_code' => $customerAddress->postal ?? '',
                'province' => $customerAddress->province ?? 'AB',
            ]);

            // Map old customer ID to new customer ID
            $this->customerIdMap[$oldCustomer->customerID] = $customer->id;

            $bar->advance();
            $i++;
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . count($oldCustomers) . ' customers');
    }

    private function migrateOneTimeOrders()
    {
        $oldOrders = DB::connection('old_mysql')
            ->table('orders')
            ->where('recurring', 0)
            ->where('recurringInstance', 0)
            ->get();

        $bar = $this->output->createProgressBar(count($oldOrders));
        $bar->start();

        foreach ($oldOrders as $oldOrder) {
            $this->createOrderFromOld($oldOrder, Order::NON_RECURRING);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . count($oldOrders) . ' one-time orders');
    }

    private function migrateRecurringParentOrders()
    {
        $oldOrders = DB::connection('old_mysql')
            ->table('orders')
            ->where('recurring', 1)
            ->where('recurringInstance', 0)
            ->get();

        $bar = $this->output->createProgressBar(count($oldOrders));
        $bar->start();

        foreach ($oldOrders as $oldOrder) {
            $this->createOrderFromOld($oldOrder, Order::RECURRING);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . count($oldOrders) . ' recurring parent orders');
    }

    private function migrateRecurringInstances()
    {
        $oldInstances = DB::connection('old_mysql')
            ->table('orders')
            ->where('recurringInstance', 1)
            ->orderBy('orderDate', 'asc')
            ->get();

        $bar = $this->output->createProgressBar(count($oldInstances));
        $bar->start();

        foreach ($oldInstances as $oldInstance) {
            $this->createRecurringInstanceFromOld($oldInstance);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . count($oldInstances) . ' recurring instances');
    }

    private function createOrderFromOld($oldOrder, $recurringType)
    {
        // Get new customer ID
        $newCustomerId = $this->customerIdMap[$oldOrder->customerID] ?? null;

        if (!$newCustomerId) {
            $this->warn("Customer not found for order {$oldOrder->orderID}");
            return;
        }

        $customer = Customer::find($newCustomerId);

        // Determine status
        $status = Order::VALID;
        if ($oldOrder->cancelled == 1) {
            $status = Order::CANCELLED;
        } elseif ($oldOrder->recurSkip == 1) {
            $status = Order::SKIP;
        } elseif (strtotime($oldOrder->orderDate) < strtotime('-1 day')) {
            $status = Order::COMPLETED;
        }

        // Get delivery date
        $deliveryDate = $oldOrder->orderDate ? Carbon::createFromTimestamp($oldOrder->orderDate) : Carbon::now();

        // Calculate costs
        $iceAmount = (float)$oldOrder->count;
        $boxAmount = $this->extractBoxAmount($oldOrder->notes); // Try to extract from notes

        $iceUnitPrice = 1.95;
        $boxUnitPrice = 30.00;

        $iceTotal = $iceAmount * $iceUnitPrice;
        $boxTotal = $boxAmount * $boxUnitPrice;
        $subTotal = $iceTotal + $boxTotal;

        // Estimate delivery cost (default $25 for delivery, $0 for pickup)
        $deliveryCost = $oldOrder->delivery == 1 ? 25.00 : 0.00;

        // Calculate tax (5% GST on ice, 12% on boxes)
        $tax = ($iceTotal * 0.05) + ($boxTotal * 0.12) + ($deliveryCost * 0.05);
        $totalCost = $subTotal + $deliveryCost + $tax;

        // Create order
        $order = Order::create([
            'customer_id' => $newCustomerId,
            'customer_name' => $customer->name,
            'email' => $customer->email,
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
            'payment_status' => 'paid',
            'supplier_id' => null,
            'invoice_id' => null,
        ]);

        // Map old order ID to new order ID
        $this->orderIdMap[$oldOrder->orderID] = $order->id;

        // Create order items for ice
        if ($iceAmount > 0) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => 1, // Ice product
                'amount_of_items' => $iceAmount,
                'unit_price' => $iceUnitPrice,
                'total_price' => $iceTotal,
            ]);
        }

        // Create order items for boxes
        if ($boxAmount > 0) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => 2, // Box product
                'amount_of_items' => $boxAmount,
                'unit_price' => $boxUnitPrice,
                'total_price' => $boxTotal,
            ]);
        }
    }

    private function createRecurringInstanceFromOld($oldInstance)
    {
        // Get new customer ID
        $newCustomerId = $this->customerIdMap[$oldInstance->customerID] ?? null;

        if (!$newCustomerId) {
            $this->warn("Customer not found for recurring instance {$oldInstance->orderID}");
            return;
        }

        // Extract parent order ID from notes
        $parentOrderId = $this->extractParentOrderId($oldInstance->notes);

        if (!$parentOrderId) {
            $this->warn("Could not find parent order ID in notes for instance {$oldInstance->orderID}");
            return;
        }

        // Get new parent order ID
        $newParentOrderId = $this->orderIdMap[$parentOrderId] ?? null;

        if (!$newParentOrderId) {
            $this->warn("Parent order not found for instance {$oldInstance->orderID} (parent: {$parentOrderId})");
            return;
        }

        // Determine status
        $status = RecurringOrder::OPEN;
        if ($oldInstance->cancelled == 1) {
            $status = RecurringOrder::CANCELLED;
        } elseif (strtotime($oldInstance->orderDate) < strtotime('-1 day')) {
            $status = RecurringOrder::COMPLETED;
        }

        // Get delivery date
        $deliveryDate = $oldInstance->orderDate ? Carbon::createFromTimestamp($oldInstance->orderDate) : Carbon::now();

        // Get parent order for cost calculation
        $parentOrder = Order::find($newParentOrderId);

        // Create recurring order
        $recurringOrder = RecurringOrder::create([
            'order_id' => $newParentOrderId,
            'scheduled_delivery_date' => $deliveryDate,
            'status' => $status,
            'recurring_payment_status' => 'pending',
        ]);
    }

    /**
     * Extract parent order ID from notes like "Instance of Order# 123"
     */
    private function extractParentOrderId($notes)
    {
        if (preg_match('/Instance of Order#\s*(\d+)/i', $notes, $matches)) {
            return (int)$matches[1];
        }
        return null;
    }

    /**
     * Try to extract box amount from notes (if mentioned)
     * This is a best-effort extraction
     */
    private function extractBoxAmount($notes)
    {
        // Look for patterns like "2 boxes", "1 box", etc.
        if (preg_match('/(\d+)\s*box(es)?/i', $notes, $matches)) {
            return (int)$matches[1];
        }
        return 0;
    }
}
