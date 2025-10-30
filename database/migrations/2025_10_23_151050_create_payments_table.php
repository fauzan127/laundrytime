<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Snapshot dari orders
            $table->string('order_number')->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->string('customer_name')->nullable();

            // Info pembayaran
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('Belum Dibayar');
            $table->decimal('amount', 12, 2);
            $table->timestamp('paid_at')->nullable();

            // Token Midtrans (opsional)
            $table->string('token')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};