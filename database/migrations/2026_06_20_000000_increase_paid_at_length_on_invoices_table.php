<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Widen invoices.paid_at so it can store a human-readable date AND time
     * (e.g. "2026-06-19 21:09:23" = 19 chars). The previous length (12) only
     * fit a date and caused "Data too long for column 'paid_at'" on payment.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('paid_at', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('paid_at', 12)->nullable()->change();
        });
    }
};
