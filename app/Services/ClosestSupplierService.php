<?php

namespace App\Services;

use App\Models\SupplyLocation;
use Illuminate\Support\Facades\Http;

class ClosestSupplierService
{
    protected $googleApiKey;

    public function __construct()
    {
        // Load from config (which uses env internally)
        $this->googleApiKey = config('services.google.api_key');
    }


    public function findClosest($street, $city, $province, $useGoogle = true)
    {
        $destination = $this->formatAddress($street, $city, $province);

        $suppliers = SupplyLocation::where('province', $province)
            ->where('active', 1)
            ->get();

        if ($suppliers->isEmpty()) return null;

        if (!$useGoogle) {
            $suppliers[0]->distance = 12345;
            return $suppliers[0];
        }

        $origins = $suppliers->map(function ($supplier) {
            return $this->formatAddress($supplier->address, $supplier->city, $supplier->province);
        })->implode('|');

        $response = Http::get("https://maps.googleapis.com/maps/api/distancematrix/json", [
            'key' => $this->googleApiKey,
            'units' => 'imperial',
            'origins' => $origins,
            'destinations' => $destination,
        ]);

        $data = $response->json();

        if (!isset($data['rows'])) return null;

        $shortest = PHP_INT_MAX;
        $closest = null;

        foreach ($data['rows'] as $index => $row) {
            $distance = $row['elements'][0]['distance']['value'] ?? null;

            if ($distance !== null && $distance < $shortest) {
                $shortest = $distance;
                $closest = $suppliers[$index];
                $closest->distance = $distance;
            }
        }

        return $closest;
    }

    protected function formatAddress($street, $city, $province)
    {
        return urlencode(trim("{$street}, {$city}, {$province}"));
    }

}
