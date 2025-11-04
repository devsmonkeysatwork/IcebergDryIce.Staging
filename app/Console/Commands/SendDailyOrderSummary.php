<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\RecurringOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDailyOrderSummary extends Command
{
    protected $signature = 'orders:send-daily-summary';
    protected $description = 'Send daily order summary to admin';

    public function handle()
    {
        $today = Carbon::today();
        $nextWeek = $today->copy()->addWeek();

        // Get today's orders (single orders)
        $todayOrders = Order::whereDate('delivery_date', $today)
            ->where('status', '!=', Order::COMPLETED)
            ->with(['items.product', 'customer'])
            ->get();

        // Get today's recurring orders
        $nextRecurringOrders = RecurringOrder::whereDate('scheduled_delivery_date', $nextWeek)
            ->where('status', '=', RecurringOrder::OPEN)
            ->with(['order.items.product', 'order.customer'])
            ->get();


        Mail::send('emails.orders-summary', [
            'todayOrders' => $todayOrders,
            'nextRecurringOrders' => $nextRecurringOrders,
            'date' => $today,
            'nextWeek' => $nextWeek->format('F d, Y')
        ], function ($message) use ($today) {
            $message->to(env('ADMIN_EMAIL'))
                ->subject('Daily Order Summary - ' . $today->format('F d, Y'));
        });

        $this->info("Daily order summary sent to admin");
        return 0;
    }
}
