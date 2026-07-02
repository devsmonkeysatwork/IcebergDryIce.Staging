<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplyLocation;

class SupplyLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Praxair',
                'address' => '2080 Clark Drive',
                'city' => 'Vancouver',
                'province' => 'BC',
                'country' => 'Canada',
                'postal' => 'V5N3G7',
                'ice_cost' => 0.55,
                'courier' => 0,
                'active' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Kelowna',
                'address' => '2525 Acland Rd',
                'city' => 'Kelowna',
                'province' => 'BC',
                'country' => 'Canada',
                'postal' => 'V1X7J4',
                'ice_cost' => 0.55,
                'courier' => 0,
                'active' => 0,
            ],
            [
                'id' => 3,
                'name' => 'Calgary',
                'address' => '609 42 Ave SE',
                'city' => 'Calgary',
                'province' => 'AB',
                'country' => 'Canada',
                'postal' => 'T2G1Y7',
                'ice_cost' => 0.55,
                'courier' => 1,
                'active' => 1,
            ],
            [
                'id' => 99,
                'name' => 'Pacific Dry Ice',
                'address' => '9625 32nd Ave Ct S',
                'city' => 'Lakewood',
                'province' => 'Washington',
                'country' => 'US',
                'postal' => '98499',
                'ice_cost' => 0.42,
                'courier' => 0,
                'active' => 1,
            ]
        ];

        foreach ($data as $item) {
            SupplyLocation::create($item);
        }
    }
}
