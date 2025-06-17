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



    public function getNovexQuote(Request $request)
    {
        try {
            // Hardcoded test data
            $testData = [
                'delivery' => [
                    'street' => '1055 Canada Pl',
                    'city' => 'Vancouver',
                    'province' => 'BC',
                    'postalCode' => 'V6C 0C3',
                    'contact' => 'Test Contact',
                    'phone' => '604-555-1234',
                ],
                'supplier' => [
                    'name' => 'Test Supplier Inc',
                    'street' => '123 Test St',
                    'city' => 'Vancouver',
                    'province' => 'BC',
                    'postalCode' => 'V5K 1A1',
                    'contact' => 'Supplier Contact',
                    'phone' => '604-555-5678',
                ],
                'readyBy' => '2025-06-17T10:00:00',
                'serviceTypeId' => 1,
                'vehicleTypeId' => 1,
                'packages' => [
                    [
                        'typeId' => 1,
                        'count' => 1,
                        'weight' => 5.0,
                        'length' => 12,
                        'width' => 12,
                        'height' => 12,
                    ]
                ],
            ];

            // Assign test data to variables
            $delivery = $testData['delivery'];
            $supplier = $testData['supplier'];
            $readyBy = $testData['readyBy'];
            $serviceTypeId = $testData['serviceTypeId'];
            $vehicleTypeId = $testData['vehicleTypeId'];
            $packages = $testData['packages'];

            // 1. Validate required data
            if (!$delivery || !$supplier) {
                Log::error('Missing delivery or supplier data', [
                    'delivery' => $delivery,
                    'supplier' => $supplier
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Missing delivery or supplier information'
                ], 400);
            }

            // 2. Validate delivery address fields
            $requiredDeliveryFields = ['street', 'city', 'province', 'postalCode', 'contact', 'phone'];
            foreach ($requiredDeliveryFields as $field) {
                if (empty($delivery[$field])) {
                    Log::error("Missing required delivery field: {$field}", $delivery);
                    return response()->json([
                        'success' => false,
                        'error' => "Missing required delivery field: {$field}"
                    ], 400);
                }
            }

            // Build the pickup payload from supplier data
            $pickupPayload = [
                'name' => $supplier['name'] ?? 'Supply Location',
                'street' => trim($supplier['street'] ?? ''),
                'unit' => $supplier['unit'] ?? '',
                'city' => trim($supplier['city'] ?? ''),
                'province' => $this->normalizeProvince($supplier['province'] ?? ''),
                'postalCode' => $this->normalizePostalCode($supplier['postalCode'] ?? ''),
                'country' => 'CA',
                'instructions' => '',
                'close' => '17:30',
                'contact' => $supplier['contact'] ?? 'Supply Manager',
                'phone' => $this->normalizePhone($supplier['phone'] ?? '604-555-0100'),
                'fax' => '',
                'notificationEmail' => $supplier['email'] ?? '',
                'notifyOn' => ['Dispatch']
            ];

            // Validate pickup address
            if (empty($pickupPayload['street']) || empty($pickupPayload['city']) || empty($pickupPayload['province']) || empty($pickupPayload['postalCode'])) {
                Log::error('Incomplete pickup address after processing', [
                    'supplier_data' => $supplier,
                    'pickup_payload' => $pickupPayload
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Incomplete pickup address: missing street, city, province, or postal code'
                ], 400);
            }

            // Build delivery payload
            $deliveryPayload = [
                'name' => $delivery['name'] ?? 'Delivery Location',
                'street' => trim($delivery['street']),
                'unit' => $delivery['unit'] ?? '',
                'city' => trim($delivery['city']),
                'province' => $this->normalizeProvince($delivery['province']),
                'postalCode' => $this->normalizePostalCode($delivery['postalCode']),
                'country' => $delivery['country'] ?? 'CA',
                'instructions' => $delivery['instructions'] ?? '',
                'close' => $delivery['close'] ?? '17:30',
                'contact' => trim($delivery['contact']),
                'phone' => $this->normalizePhone($delivery['phone']),
                'fax' => $delivery['fax'] ?? '',
                'notificationEmail' => $delivery['notificationEmail'] ?? '',
                'notifyOn' => $delivery['notifyOn'] ?? ['Dispatch']
            ];

            // Prepare packages
            $packageData = [];
            if (!empty($packages) && is_array($packages)) {
                foreach ($packages as $package) {
                    $packageData[] = [
                        'typeId' => (int)($package['typeId'] ?? 1),
                        'count' => (int)($package['count'] ?? 1),
                        'weight' => (float)($package['weight'] ?? 5.0),
                        'length' => (float)($package['length'] ?? 12),
                        'width' => (float)($package['width'] ?? 12),
                        'height' => (float)($package['height'] ?? 12),
                    ];
                }
            } else {
                $packageData[] = [
                    'typeId' => 1,
                    'count' => 1,
                    'weight' => 5.0,
                    'length' => 12,
                    'width' => 12,
                    'height' => 12,
                ];
            }

            // Format readyBy date
            $formattedReadyBy = $readyBy ?
                date('Y-m-d\TH:i:s', strtotime($readyBy)) :
                date('Y-m-d\TH:i:s', strtotime('+1 hour'));

            // Build the complete quote payload
            $quotePayload = [
                'callerName' => 'Ice Delivery Admin',
                'callerPhone' => '604-555-5555',
                'department' => 'Ice Dispatch',
                'reference' => 'ICE-' . date('Ymd') . '-' . rand(1000, 9999),
                'pickup' => $pickupPayload,
                'delivery' => $deliveryPayload,
                'serviceTypeId' => (int)$serviceTypeId,
                'vehicleTypeId' => (int)$vehicleTypeId,
                'readyBy' => $formattedReadyBy,
                'instructions' => 'Dry ice delivery - handle with care',
                'packages' => $packageData,
                'promoCode' => 'Promo1',
            ];

            // Log the payload for debugging
            Log::info('Complete Novex Quote Payload:', $quotePayload);


// Generate guest credential
            $guestCredential = 'Guest:' . Str::uuid();
            $encodedAuth = base64_encode($guestCredential);

// Log for debugging
            Log::info('Attempting Basic Auth with:', [
                'credential' => $guestCredential,
                'base64_encoded' => $encodedAuth
            ]);

// Send API request
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $encodedAuth,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
                ->timeout(30)
                ->retry(2, 1000)
                ->post('https://api.novex.ca/sandbox/quote', $quotePayload);

// Check response
            if ($response->successful()) {
                Log::info('API Response:', $response->json());
                return $response->json();
            } else {
                Log::error('API Error:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [
                    'success' => false,
                    'error' => 'Request failed: ' . $response->body()
                ];
            }

            // Handle successful response
            if ($response->successful()) {
                $responseData = $response->json();
                $total = $responseData['total'] ??
                    $responseData['price'] ??
                    $responseData['amount'] ??
                    $responseData['cost'] ??
                    20.00; // Fallback value

                return response()->json([
                    'success' => true,
                    'quote' => $responseData,
                    'total' => $total
                ]);
            }

            // Handle API errors
            $errorBody = $response->body();
            $statusCode = $response->status();

            Log::error('Novex API Error Response:', [
                'status' => $statusCode,
                'body' => $errorBody,
                'payload_sent' => $quotePayload
            ]);

            $errorMessage = 'API request failed';
            if ($response->json() && isset($response->json()['message'])) {
                $errorMessage = $response->json()['message'];
            } elseif ($response->json() && isset($response->json()['error'])) {
                $errorMessage = $response->json()['error'];
            }

            return response()->json([
                'success' => false,
                'error' => $errorMessage,
                'status' => $statusCode,
                'details' => $errorBody
            ], 422);

        } catch (\Exception $e) {
            Log::error('Novex API Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Request failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Assuming these helper methods exist in your class
    private function normalizeProvince($province)
    {
        // Dummy implementation; replace with your actual logic
        return strtoupper($province);
    }

    private function normalizePostalCode($postalCode)
    {
        // Dummy implementation; replace with your actual logic
        return strtoupper(preg_replace('/\s+/', '', $postalCode));
    }

    private function normalizePhone($phone)
    {
        // Dummy implementation; replace with your actual logic
        return preg_replace('/[^0-9]/', '', $phone);
    }


    /*public function getNovexQuote(Request $request)
    {
        try {


            // 1. Validate required data
            $delivery = $request->input('delivery');
            $supplier = $request->input('supplier');
            $readyBy = $request->input('readyBy');
            $serviceTypeId = $request->input('serviceTypeId', 1);
            $vehicleTypeId = $request->input('vehicleTypeId', 1);
            $packages = $request->input('packages', []);

            if (!$delivery || !$supplier) {
                Log::error('Missing delivery or supplier data', [
                    'delivery' => $delivery,
                    'supplier' => $supplier
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Missing delivery or supplier information'
                ], 400);
            }

            // 2. Validate delivery address fields
            $requiredDeliveryFields = ['street', 'city', 'province', 'postalCode', 'contact', 'phone'];
            foreach ($requiredDeliveryFields as $field) {
                if (empty($delivery[$field])) {
                    Log::error("Missing required delivery field: {$field}", $delivery);
                    return response()->json([
                        'success' => false,
                        'error' => "Missing required delivery field: {$field}"
                    ], 400);
                }
            }

//              Build the pickup payload from supplier data
            // Build the pickup payload from supplier data
            $pickupPayload = [
                'name' => $supplier['name'] ?? 'Supply Location',
                'street' => trim($supplier['address'] ?? $supplier['street'] ?? ''),
                'unit' => $supplier['unit'] ?? '',
                'city' => trim($supplier['city'] ?? ''),
                'province' => $this->normalizeProvince($supplier['province'] ?? ''),
                'postalCode' => $this->normalizePostalCode($supplier['postal'] ?? $supplier['postalCode'] ?? ''),
                'country' => 'CA',
                'instructions' => '',
                'close' => '17:30',
                'contact' => $supplier['contact'] ?? $supplier['name'] ?? 'Supply Manager',
                'phone' => $this->normalizePhone($supplier['phone'] ?? '604-555-0100'),
                'fax' => '',
                'notificationEmail' => $supplier['email'] ?? '',
                'notifyOn' => ['Dispatch']
            ];

// Add validation for pickup address BEFORE sending to API
            if (empty($pickupPayload['street']) || empty($pickupPayload['city']) || empty($pickupPayload['province']) || empty($pickupPayload['postalCode'])) {
                Log::error('Incomplete pickup address after processing', [
                    'supplier_data' => $supplier,
                    'pickup_payload' => $pickupPayload
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Incomplete pickup address: missing street, city, province, or postal code'
                ], 400);
            }

//              Build delivery payload with proper validation
            $deliveryPayload = [
                'name' => $delivery['name'] ?? 'Delivery Location',
                'street' => trim($delivery['street']),
                'unit' => $delivery['unit'] ?? '',
                'city' => trim($delivery['city']),
                'province' => $this->normalizeProvince($delivery['province']),
                'postalCode' => $this->normalizePostalCode($delivery['postalCode']),
                'country' => $delivery['country'] ?? 'CAN',
                'instructions' => $delivery['instructions'] ?? '',
                'close' => $delivery['close'] ?? '17:30',
                'contact' => trim($delivery['contact']),
                'phone' => $this->normalizePhone($delivery['phone']),
                'fax' => $delivery['fax'] ?? '',
                'notificationEmail' => $delivery['notificationEmail'] ?? '',
                'notifyOn' => $delivery['notifyOn'] ?? ['Dispatch']
            ];




            // 6. Prepare packages with proper validation
            $packageData = [];
            if (!empty($packages) && is_array($packages)) {
                foreach ($packages as $package) {
                    $packageData[] = [
                        'typeId' => (int)($package['typeId'] ?? 1),
                        'count' => (int)($package['count'] ?? 1),
                        'weight' => (float)($package['weight'] ?? 5.0),
                        'length' => (float)($package['length'] ?? 12),
                        'width' => (float)($package['width'] ?? 12),
                        'height' => (float)($package['height'] ?? 12),
                    ];
                }
            } else {
                // Default package
                $packageData[] = [
                    'typeId' => 1,
                    'count' => 1,
                    'weight' => 5.0,
                    'length' => 12,
                    'width' => 12,
                    'height' => 12,
                ];
            }

            // 7. Format readyBy date properly
            $formattedReadyBy = $readyBy ?
                date('Y-m-d\TH:i:s', strtotime($readyBy)) :
                date('Y-m-d\TH:i:s', strtotime('+1 hour'));

            // 8. Build the complete quote payload
            $quotePayload = [
                'callerName' => 'Ice Delivery Admin',
                'callerPhone' => '604-555-5555',
                'department' => 'Ice Dispatch',
                'reference' => 'ICE-' . date('Ymd') . '-' . rand(1000, 9999),
                'pickup' => $pickupPayload,
                'delivery' => $deliveryPayload,
                'serviceTypeId' => (int)$serviceTypeId,
                'vehicleTypeId' => (int)$vehicleTypeId,
                'readyBy' => $formattedReadyBy,
                'instructions' => 'Dry ice delivery - handle with care',
                'packages' => $packageData,
                'promoCode' => 'Promo1',
            ];

            // Log the complete payload for debugging
            Log::info('Complete Novex Quote Payload:', $quotePayload);

            // 9. Send to Novex API with proper error handling
            $username = 'iceberg_Novex';
            $password = 'Ktttyk';

            Log::info('Attempting Basic Auth with:', [
                'username' => $username,
                'password' => str_repeat('*', strlen($password)), // Hide actual password in logs
                'base64_encoded' => base64_encode($username . ':' . $password)
            ]);

            $guestCredential = 'Guest:' . Str::uuid();
            $encodedAuth = base64_encode($guestCredential);

            Log::info('Novex Auth Debug:', [
                'raw_credential' => $guestCredential,
                'base64_encoded' => $encodedAuth,
                'full_header' => 'Basic ' . $encodedAuth
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $encodedAuth,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
                ->timeout(30)
                ->retry(2, 1000)
                ->post('https://api.novex.ca/sandbox/quote', $quotePayload);

// Log the response for debugging
            Log::info('Novex API Response:', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            // 10. Handle successful response
            if ($response->successful()) {
                $responseData = $response->json();

                // Extract the quote total - Novex API might return different field names
                $total = $responseData['total'] ??
                    $responseData['price'] ??
                    $responseData['amount'] ??
                    $responseData['cost'] ??
                    20.00; // fallback

                return response()->json([
                    'success' => true,
                    'quote' => $responseData,
                    'total' => $total
                ]);
            }

            // 11. Handle API errors
            $errorBody = $response->body();
            $statusCode = $response->status();

            Log::error('Novex API Error Response:', [
                'status' => $statusCode,
                'body' => $errorBody,
                'payload_sent' => $quotePayload
            ]);

            // Try to parse error message
            $errorMessage = 'API request failed';
            if ($response->json() && isset($response->json()['message'])) {
                $errorMessage = $response->json()['message'];
            } elseif ($response->json() && isset($response->json()['error'])) {
                $errorMessage = $response->json()['error'];
            }

            return response()->json([
                'success' => false,
                'error' => $errorMessage,
                'status' => $statusCode,
                'details' => $errorBody
            ], 422); // Return 422 instead of original status to avoid frontend issues

        } catch (\Exception $e) {
            Log::error('Novex API Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Request failed: ' . $e->getMessage()
            ], 500);
        }
    }*/
//
//    /**
//     * Normalize province code to standard format
//     */
//    private function normalizeProvince($province)
//    {
//        $provinceMap = [
//            'alberta' => 'AB',
//            'british columbia' => 'BC',
//            'manitoba' => 'MB',
//            'new brunswick' => 'NB',
//            'newfoundland and labrador' => 'NL',
//            'northwest territories' => 'NT',
//            'nova scotia' => 'NS',
//            'nunavut' => 'NU',
//            'ontario' => 'ON',
//            'prince edward island' => 'PE',
//            'quebec' => 'QC',
//            'saskatchewan' => 'SK',
//            'yukon' => 'YT'
//        ];
//
//        $province = trim(strtolower($province));
//        return $provinceMap[$province] ?? strtoupper($province);
//    }
//
//    /**
//     * Normalize postal code format
//     */
//    private function normalizePostalCode($postalCode)
//    {
//        // Remove spaces and convert to uppercase
//        $postalCode = strtoupper(str_replace(' ', '', trim($postalCode)));
//
//        // Add space in middle if 6 characters
//        if (strlen($postalCode) === 6) {
//            $postalCode = substr($postalCode, 0, 3) . ' ' . substr($postalCode, 3);
//        }
//
//        return $postalCode;
//    }
//
//    /**
//     * Normalize phone number format
//     */
//    private function normalizePhone($phone)
//    {
//        // Remove all non-digit characters
//        $phone = preg_replace('/\D/', '', trim($phone));
//
//        // Add default area code if needed
//        if (strlen($phone) === 7) {
//            $phone = '604' . $phone;
//        }
//
//        // Format as XXX-XXX-XXXX
//        if (strlen($phone) === 10) {
//            $phone = substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
//        }
//
//        return $phone ?: '604-555-0100'; // fallback
//    }
}
