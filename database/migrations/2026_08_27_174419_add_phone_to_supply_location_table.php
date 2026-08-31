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
        Schema::table('supply_location', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('postal');
        });

        // Confirmed by Tyler (2026-08-27): Praxair pickup contact.
        DB::table('supply_location')
            ->where('name', 'Praxair')
            ->update(['phone' => '604-255-6007']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supply_location', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
