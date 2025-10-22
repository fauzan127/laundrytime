<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    ppublic function up()
    {
        Schema::create('kain_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kain');
            $table->integer('jumlah');
            $table->string('supplier');
            $table->date('tanggal_masuk');
            $table->timestamps();
        });
    }
    