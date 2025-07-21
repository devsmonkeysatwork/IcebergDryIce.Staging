<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\RecurringOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessRecurringOrders extends Command
{
    protected $signature = 'orders:process-recurring';
    protected $description = 'Process recurring orders and create instances';

    public function handle()
    {
        $today = Carbon::now();
        $todayWeekday = $today->dayOfWeek;

        $recurringOrders = Order::where('recurring', Order::RECURRING)->get();
//        Log::info($todayWeekday);
//        Log::info(count($recurringOrders));
        foreach ($recurringOrders as $order) {
            $deliveryDate = Carbon::parse($order->delivery_date);
            $orderWeekday = $deliveryDate->dayOfWeek;
//            Log::info('------------------------');
//            Log::info($orderWeekday);
            if ($orderWeekday === $todayWeekday) {
                $nextDeliveryDate = $today->copy()->addWeek();

                $existingRecurring = RecurringOrder::where('order_id', $order->id)
                    ->where('scheduled_delivery_date', $nextDeliveryDate->toDateString())
                    ->exists();

                if (!$existingRecurring) {
                    RecurringOrder::create([
                        'order_id' => $order->id,
                        'scheduled_delivery_date' => $nextDeliveryDate,
                        'status' => 'open',
                    ]);

                    $this->info("Created recurring order for Order ID: {$order->id}");
                }
            }
        }

        $this->info('Recurring orders processing completed.');
    }
}
