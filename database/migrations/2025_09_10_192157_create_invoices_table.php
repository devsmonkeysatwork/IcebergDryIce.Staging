<?php

// ============================================
// MIGRATION 1: Create invoices table
// ============================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: 2024_01_01_create_invoices_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // INV-2024-000001
            $table->string('invoice_type')->default('one_time'); // 'one_time' or 'recurring'

            // Polymorphic relationship to Order or RecurringOrder
            $table->morphs('invoiceable');

            // Financial fields
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');


            $table->unsignedBigInteger('parent_invoice_id')->nullable();
            $table->integer('recurring_sequence')->default(1);


            $table->date('invoice_date');
            $table->timestamps();

            $table->index('parent_invoice_id');
            $table->foreign('parent_invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
