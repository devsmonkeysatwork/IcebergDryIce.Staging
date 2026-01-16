<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateOrderSupplierIds extends Command
{
    protected $signature = 'orders:update-supplier-ids {--verify : Only verify without updating}';
    protected $description = 'Update supplier_id for all orders from old database';

    public function handle()
    {
        $verify = $this->option('verify');

        if ($verify) {
            $this->info('🔍 VERIFICATION MODE - No changes will be made');
            $this->verifySupplierIds();
        } else {
            if (!$this->confirm('This will update supplier_id for all orders. Continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
            $this->updateSupplierIds();
        }

        return 0;
    }

    private function verifySupplierIds()
    {
        $this->info('Verifying supplier IDs...');

        $orders = Order::limit(20)->get();
        $data = [];

        foreach ($orders as $order) {
            $oldCustomer = DB::connection('old_mysql')
                ->table('login')
                ->where('customerID', $order->customer_id)
                ->first();

            $currentSupplierId = $order->supplier_id;
            $correctSupplierId = $oldCustomer->supply_location ?? 'NOT FOUND';
            $match = $currentSupplierId == $correctSupplierId ? '✅' : '❌';

            $data[] = [
                'Order ID' => $order->id,
                'Customer ID' => $order->customer_id,
                'Customer Name' => $order->customer_name,
                'Current Supplier' => $currentSupplierId ?? 'NULL',
                'Should Be' => $correctSupplierId,
                'Match' => $match,
            ];
        }

        $this->table(
            ['Order ID', 'Customer ID', 'Customer Name', 'Current Supplier', 'Should Be', 'Match'],
            $data
        );

        $this->newLine();
        $this->info('Showing first 20 orders. Run without --verify to update all orders.');
    }

    private function updateSupplierIds()
    {
        $this->info('Updating supplier_id for all orders...');

        // First, reset all supplier_ids to NULL
        $this->info('Resetting all supplier_ids to NULL...');
        Order::query()->update(['supplier_id' => null]);

        $orders = Order::all();
        $bar = $this->output->createProgressBar(count($orders));
        $bar->start();

        $updated = 0;
        $failed = 0;
        $notFound = 0;

        foreach ($orders as $order) {
            try {
                // Get customer from old database
                $oldCustomer = DB::connection('old_mysql')
                    ->table('login')
                    ->where('customerID', $order->customer_id)
                    ->first();

                if (!$oldCustomer) {
                    $notFound++;
                    Log::warning("Customer not found in old database", [
                        'order_id' => $order->id,
                        'customer_id' => $order->customer_id,
                    ]);
                } elseif (isset($oldCustomer->supply_location) && $oldCustomer->supply_location > 0) {
                    // Update supplier_id
                    $order->update(['supplier_id' => $oldCustomer->supply_location]);
                    $updated++;

                    Log::info("Updated order supplier_id", [
                        'order_id' => $order->id,
                        'customer_id' => $order->customer_id,
                        'customer_name' => $oldCustomer->login ?? 'Unknown',
                        'supplier_id' => $oldCustomer->supply_location,
                    ]);
                } else {
                    // supply_location is 0 or NULL - leave as NULL
                    Log::info("No supplier location for customer", [
                        'order_id' => $order->id,
                        'customer_id' => $order->customer_id,
                        'supply_location' => $oldCustomer->supply_location ?? 'NULL',
                    ]);
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error("Failed to update order", [
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'error' => $e->getMessage(),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Status', 'Count'],
            [
                ['Updated (supplier_id set)', $updated],
                ['No Supplier (left as NULL)', $orders->count() - $updated - $failed - $notFound],
                ['Customer Not Found', $notFound],
                ['Failed/Errors', $failed],
                ['Total Orders', count($orders)],
            ]
        );

        $this->info('✅ Supplier IDs update completed!');
        $this->info('💡 Check storage/logs/laravel.log for detailed information');
    }
}
