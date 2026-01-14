<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE invoices AUTO_INCREMENT = 3001');
    }

    public function down()
    {
        DB::statement('ALTER TABLE invoices AUTO_INCREMENT = 1');
    }
};
