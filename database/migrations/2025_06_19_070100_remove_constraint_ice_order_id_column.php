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
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['ice_order_id']);
            $table->renameColumn('ice_order_id', 'order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->renameColumn('order_id', 'ice_order_id');
            $table->foreign('ice_order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }
};
