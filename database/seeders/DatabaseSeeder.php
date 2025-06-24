<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the RolesAndPermissionsSeeder
        $this->call(RolesAndPermissionsSeeder::class);

        // Create a test user and assign the admin role
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@icebergdryice.com',
            'password' => bcrypt('password'),
        ]);

        $adminUser->assignRole('admin');
    }
}
