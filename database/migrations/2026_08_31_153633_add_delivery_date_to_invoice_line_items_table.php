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
        Schema::table('invoice_line_items', function (Blueprint $table) {
            // Lets each line (product or per-order fee) carry its own delivery
            // date, matching the reference invoice's one-line-per-delivery format.
            $table->timestamp('delivery_date')->nullable()->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_line_items', function (Blueprint $table) {
            $table->dropColumn('delivery_date');
        });
    }
};
