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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->json('layanan'); // array of services
            $table->enum('jenis_pengantaran', ['antar_jemput', 'pengantaran_pribadi']);
            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['Pending', 'Proses', 'Selesai'])->default('Pending');
            $table->decimal('berat', 5, 2)->nullable(); // weight in kg
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
