<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds a draft/finalized lifecycle to invoices so consolidated invoices can
     * be edited until explicitly finalized.
     * - `status` defaults to 'finalized' so every existing/legacy/online invoice
     *   keeps behaving exactly as before; only the new editable consolidated
     *   drafts are created with status='draft'.
     * - `invoice_number` becomes nullable because a draft has no number until it
     *   is finalized (MySQL allows multiple NULLs under the existing unique index).
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status', 20)->default('finalized')->after('invoice_type');
            $table->string('invoice_number')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
