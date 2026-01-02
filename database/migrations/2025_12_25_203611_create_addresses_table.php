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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('address_label', 100)->nullable()->comment('e.g., Main Location, Store #1');
            $table->string('location_name')->nullable();
            $table->string('address');
            $table->string('unit')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->enum('province', ['BC', 'AB']);
            $table->string('country')->default('Canada');
            $table->boolean('is_default')->default(false)->comment('Primary/default address for customer');
            $table->boolean('is_active')->default(true)->comment('Soft delete flag');
            $table->text('delivery_instructions')->nullable()->comment('Special delivery notes for this address');
            $table->timestamps();

            // Indexes
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->index('customer_id');
            $table->index('is_default');
            $table->index('is_active');
            $table->index(['customer_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
