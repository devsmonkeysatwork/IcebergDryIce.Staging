<?php

use Illuminate\Support\Facades\Route;

// For web.php (browser testing)
Route::get('/test-novex-quote', function () {
    $controller = new App\Http\Controllers\SupplierController();

    // Create a fake request with minimal data
    $request = new \Illuminate\Http\Request();
    $request->merge([
        'delivery' => [
            'name' => 'Test Delivery',
            'street' => '800 Robson Street',
            'city' => 'Vancouver',
            'province' => 'BC',
            'postalCode' => 'V6Z 3A7',
            'contact' => 'Jane Smith',
            'phone' => '604-555-0200'
        ],
        'supplier' => [
            'name' => 'Test Supplier',
            'address' => '1055 West Hastings Street',
            'city' => 'Vancouver',
            'province' => 'BC',
            'postal' => 'V6E 2E9',
            'contact' => 'John Doe',
            'phone' => '604-555-0100'
        ],
        'readyBy' => now()->addHours(2)->toISOString(),
        'serviceTypeId' => 1,
        'vehicleTypeId' => 1
    ]);

    return $controller->getNovexQuote($request);
});
