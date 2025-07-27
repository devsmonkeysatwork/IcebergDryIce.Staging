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
        Schema::table('manual_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('recurring_order_id')->nullable()->after('order_number');
            $table->foreign('recurring_order_id')->references('id')->on('recurring_orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manual_payments', function (Blueprint $table) {
            $table->dropColumn('recurring_order_id');
        });
    }
};
