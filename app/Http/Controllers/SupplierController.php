<?php

namespace App\Http\Controllers;
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
            'reference' => uniqid('ICE_'), // Generate unique reference
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
                'unit' => $deliveryDetails['unit'] ?? '',
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
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . config('services.novex.auth_key'), // Store in config
                'Content-Type' => 'application/json'
            ])->post(config('services.novex.api_url', 'https://api.novex.ca/sandbox/quote'), $orderDetails);

            if ($response->successful()) {
                $data = $response->json();
                $totalAmount = $data['totalAmount'] ?? 0;

                // Add 25% markup
                $totalAmount += ($totalAmount * 0.25);

                return $totalAmount;
            } else {
                Log::error('Novex API error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'request' => $orderDetails
                ]);
                return -1;
            }
        } catch (\Exception $e) {
            Log::error('Novex API exception: ' . $e->getMessage(), [
                'request' => $orderDetails
            ]);
            return -1;
        }
    }


}
