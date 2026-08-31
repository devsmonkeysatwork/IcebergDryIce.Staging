<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('available_to_account_holders')->default(true)->after('unit');
        });

        // Confirmed by Tyler (2026-08-27): Styrofoam boxes (Dry Ice Blocks,
        // sold by the "box" unit) are public/credit-card only.
        DB::table('products')
            ->where('unit', 'box')
            ->update(['available_to_account_holders' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('available_to_account_holders');
        });
    }
};
