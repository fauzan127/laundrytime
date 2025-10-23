<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class UpdateStatusEnumInOrdersTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM('diproses', 'siap_antar', 'antar', 'sampai_tujuan', 'cancelled') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM('diproses', 'sampai_tujuan', 'cancelled') NOT NULL");
    }
};
