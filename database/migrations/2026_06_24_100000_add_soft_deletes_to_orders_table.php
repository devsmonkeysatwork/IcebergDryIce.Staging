<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add soft deletes to orders so deleting an order via the admin
     * (OrderCrudController::deleteOrderAjax) hides it instead of permanently
     * removing the row. Soft-deleted orders are automatically excluded from all
     * normal queries (lists, invoice-generator eligibility, etc.).
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
