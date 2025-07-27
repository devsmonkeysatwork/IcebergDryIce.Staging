<?php

namespace App\Console\Commands;

use App\Models\SupplyLocation;
use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\RecurringOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessRecurringOrders extends Command
{
    protected $signature = 'orders:process-recurring';
    protected $description = 'Process recurring orders and create instances';

    public function handle()
    {
        $today = Carbon::now();
        $todayWeekday = $today->dayOfWeek;

        $recurringOrders = Order::where('recurring', Order::RECURRING)->where('status',Order::VALID)->get();
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

                    Log::info("Created recurring order for Order ID: {$order->id}");
                }
            }
        }

        // Process recurring orders due in 2 days
        $twoDaysFromNow = $today->copy()->addDays(2)->toDateString();

        $recurringOrdersDue = RecurringOrder::where('scheduled_delivery_date', $twoDaysFromNow)
            ->where('status', 'open')
            ->whereNull('novex_order_id')
            ->get();

        foreach ($recurringOrdersDue as $recurringOrder) {
            $result = $this->pushToNovex($recurringOrder);
            if ($result) {
                Log::info("Pushed recurring order to Novex for Order ID: {$recurringOrder->order_id}");
            } else {
                Log::error("Failed to push recurring order to Novex for Order ID: {$recurringOrder->order_id}");
            }
        }

        Log::info('Recurring orders processing completed.');
    }

    function pushToNovex(RecurringOrder $recurringOrder)
    {
        try {
            $order = $recurringOrder->order;

            if (!$order->supplier_id) {
                Log::error('No supplier_id found for order', ['order_id' => $order->id]);
                return false;
            }

            $supplier = SupplyLocation::find($order->supplier_id);
            if (!$supplier) {
                Log::error('Supplier not found', ['supplier_id' => $order->supplier_id]);
                return false;
            }

            // Build delivery payload
            $weight = $order->amount ?: 1;
            $dimensions = max(12, ceil($weight / 2));

            $payload = [
                'callerName' => 'Tyler',
                'reference' => 'ICEBERG_TESTING_ORDER',
                'pickup' => [
                    'name' => $supplier->name,
                    'street' => $supplier->address,
                    'unit' => $supplier->unit ?? '',
                    'city' => $supplier->city,
                    'province' => $supplier->province,
                    'postalCode' => $supplier->postal,
                    'country' => 'CAN',
                    'instructions' => '',
                ],
                'delivery' => [
                    'name' => $order->location_name ?? 'Customer',
                    'street' => $order->address,
                    'unit' => $order->unit ?? '',
                    'city' => $order->city,
                    'province' => $order->province,
                    'postalCode' => $order->postal_code,
                    'country' => 'CAN',
                    'instructions' => $order->notes ?? '',
                    'contact' => $order->contact_name ?? 'Unknown',
                    'phone' => $order->phone,
                    'notificationEmail' => $order->email,
                ],
                'serviceTypeId' => 4,
                'vehicleTypeId' => 1,
                'readyBy'=> "2050-10-10T15:00-07:00",
                'instructions'=> "It is a test order.",
                'packages' => [[
                    'typeId' => 122,
                    'length' => $dimensions,
                    'width' => 1,
                    'height' => 1,
                    'weight' => $weight,
                    'count' => 1
                ]]
            ];

//            Log::info('Pushing recurring order to Novex', ['recurring_order_id' => $recurringOrder->id, 'payload' => $payload]);

            $response = Http::withOptions([
                'verify' => config('services.http_verify'),
            ])->withHeaders([
                'Authorization' => 'Basic ' . config('services.novex.auth_key'),
                'Content-Type' => 'application/json'
            ])->post(config('services.novex.push_url'), $payload);

            if ($response->successful()) {
                $body = json_decode($response->getBody());
                $recurringOrder->novex_order_id = $body->orderNumber ?? null;
                $recurringOrder->save();

                return true;
            } else {
                Log::error('Failed to push recurring order to Novex', [
                    'recurring_order_id' => $recurringOrder->id,
                    'response' => $response->body(),
                    'status' => $response->status()
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Novex Push Exception for recurring order: ' . $e->getMessage(), [
                'recurring_order_id' => $recurringOrder->id,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
