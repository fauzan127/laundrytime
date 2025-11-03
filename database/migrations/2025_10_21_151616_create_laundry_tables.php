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
        // Create service_types table
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_per_kg', 10, 2);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create clothing_types table
        Schema::create('clothing_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('additional_price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->enum('status', ['diproses', 'siap_antar', 'antar', 'sampai_tujuan', 'cancelled'])->default('diproses');
            $table->enum('delivery_type', ['antar_jemput', 'pengantaran_pribadi'])->default('pengantaran_pribadi');
            $table->text('address')->nullable();
            $table->date('pickup_date')->nullable();
            $table->time('pickup_time')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('order_date');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('payment_status', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar');
            $table->timestamp('transaction_date')->nullable();
            $table->timestamps();
        });

        // Create order_items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_type_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('clothing_type_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('weight', 8, 2);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('clothing_types');
        Schema::dropIfExists('service_types');
    }
};
