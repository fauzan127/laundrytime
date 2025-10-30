<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('token');
        });
    }

    public function down(): void {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('snap_token');
        });
    }
};