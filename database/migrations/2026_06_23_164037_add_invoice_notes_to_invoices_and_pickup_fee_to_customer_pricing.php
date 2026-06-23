<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->text('notes')->nullable();
        });
        Schema::table('customer_pricing', function (Blueprint $table) {
            $table->decimal('pickup_fee', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
        Schema::table('customer_pricing', function (Blueprint $table) {
            $table->dropColumn('pickup_fee');
        });
    }
};
