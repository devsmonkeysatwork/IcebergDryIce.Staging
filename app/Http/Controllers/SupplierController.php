<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Services\ClosestSupplierService;
use App\Models\SupplyLocation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    public function checkClosestSupplier(Request $request, ClosestSupplierService $service)
    {
        // Validate required parameters
        $street = $request->get('street');
        $city = $request->get('city');
        $province = $request->get('province');

        if (!$street || !$city || !$province) {
            return response()->json([
                'success' => false,
                'error' => 'Street, city, and province are required parameters'
            ], 400);
        }

        $closest = $service->findClosest(
            $street,
            $city,
            $province,
            true // set false to skip Google API
        );

        if (!$closest) {
            return response()->json(['message' => 'No supplier found'], 404);
        }

        return response()->json([
            'closest_supplier' => $closest,
            'distance_meters' => $closest->distance ?? 'unknown',
        ]);
    }

    public function getDeliveryQuote(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'supplier_id' => 'required|integer',
                'delivery.name' => 'required|string',
                'delivery.street' => 'required|string',
                'delivery.unit' => 'nullable|string', // Added this line
                'delivery.city' => 'required|string',
                'delivery.province' => 'required|string',
                'delivery.postal_code' => 'required|string',
                'delivery.contact' => 'required|string',
                'delivery.phone' => 'required|string',
                'delivery.email' => 'required|email',
                'weight' => 'required|numeric|min:1',
                'ready_by' => 'nullable|string'
            ]);

            // Get supplier location from database
            $supplierLocation = SupplyLocation::find($validated['supplier_id']);

            if (!$supplierLocation) {
                return response()->json([
                    'success' => false,
                    'error' => 'Supplier location not found'
                ], 404);
            }

            // Get delivery details
            $delivery = $validated['delivery'];
            $weight = $validated['weight'];

            // Calculate dimensions based on weight (you can adjust this logic)
            $dimensions = max(12, ceil($weight / 2)); // Minimum 12 inches

            // Get Novex quote
            $quoteAmount = $this->getNovexQuote(
                $supplierLocation,
                $delivery['postal_code'],
                1, // delivery type
                $weight,
                $dimensions,
                $delivery
            );

            if ($quoteAmount === -1) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unable to calculate delivery quote at this time'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'quote' => [
                    'total' => round($quoteAmount, 2)
                ],
                'total' => round($quoteAmount, 2),
                'supplier' => [
                    'id' => $supplierLocation->id,
                    'name' => $supplierLocation->name,
                    'city' => $supplierLocation->city
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors for debugging
            Log::error('Validation error in getDeliveryQuote', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Delivery quote error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while calculating the delivery quote'
            ], 500);
        }
    }

    private function getNovexQuote($supplierLocation, $destinationPostal, $deliveryType, $weight, $dimensions, $deliveryDetails)
    {
        $orderDetails = [
            'callerName' => 'Tyler',
//            'reference' => uniqid('ICE_'),
            'reference' => 'ICEBERG_TESTING_ORDER',
            'pickup' => [
                'name' => $supplierLocation->name,
                'street' => $supplierLocation->address,
                'unit' => $supplierLocation->unit ?? '',
                'city' => $supplierLocation->city,
                'province' => $supplierLocation->province,
                'postalCode' => $supplierLocation->postal,
                'country' => 'CAN',
                'instructions' => '',
            ],
            'delivery' => [
                'name' => $deliveryDetails['name'],
                'street' => $deliveryDetails['street'],
                'unit' => $deliveryDetails['unit'] ?? '', // Safe access with fallback
                'city' => $deliveryDetails['city'],
                'province' => $deliveryDetails['province'],
                'postalCode' => $destinationPostal,
                'country' => 'CAN',
                'instructions' => '',
                'contact' => $deliveryDetails['contact'],
                'phone' => $deliveryDetails['phone'],
                'notificationEmail' => $deliveryDetails['email'],
            ],
            'serviceTypeId' => 4,
            'vehicleTypeId' => 1,
            'readyBy'=> "2050-10-10T15:00-07:00",
            'instructions'=> "It is a test order.",
            'packages' => [
                [
                    'typeId' => 122,
                    'length' => $dimensions,
                    'width' => 1,
                    'height' => 1,
                    'weight' => $weight,
                    'count' => 1
                ]
            ]
        ];

        try {
            $response = Http::withOptions([
                'verify' => config('services.http_verify'),
            ])->withHeaders([
                'Authorization' => 'Basic ' . config('services.novex.auth_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.novex.api_url'), $orderDetails);

            Log::info('Novex API response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $totalAmount = $data['totalAmount'] ?? 0;

                // Add 25% markup
                $totalAmount += ($totalAmount * 0.25);

                return $totalAmount;
            } else {
                return -1;
            }
        } catch (\Exception $e) {
            return -1;
        }
    }


    public function pushToNovex($id, ClosestSupplierService $service)
    {
        try {
            $order = Order::findOrFail($id);

            if ($order->push == 1) {
                return response()->json(['success' => false, 'error' => 'Order already pushed']);
            }

            // Use ClosestSupplierService
            $supplier = $service->findClosest(
                $order->address,
                $order->city,
                $order->province,
                false // set to false if you want to skip Google API on backend
            );

            if (!$supplier || !$supplier->id) {
                return response()->json(['success' => false, 'error' => 'Closest supplier not found']);
            }

            // Build delivery payload
            $weight = $order->amount ?: 1;
            $dimensions = max(12, ceil($weight / 2));

            $payload = [
                'callerName' => 'Tyler',
//                'reference' => 'ORDER_' . $order->id,
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

            Log::info('Pushing order to Novex', ['order_id' => $order->id, 'payload' => $payload]);

            $response = Http::withOptions([
                'verify' => config('services.http_verify'),
            ])->withHeaders([
                'Authorization' => 'Basic ' . config('services.novex.auth_key'),
                'Content-Type' => 'application/json'
            ])->post(config('services.novex.push_url'), $payload);

            if ($response->successful()) {
                $order->push = 1;
                $order->save();

                return response()->json(['success' => true]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to push order',
                    'response' => $response->body()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Novex Push Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while pushing the order'
            ], 500);
        }
    }




}
