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
            $table->ulid('id')->primary();
            $table->string('order_number')->unique();
            $table->foreignUlid('user_id')->nullable()->constrained()->nullOnDelete(); // nullable para compras de invitados
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('order_status')->default('pending'); // pending, reserved, shipped, completed, cancelled
            $table->string('payment_status')->default('pending'); // pending, paid, refunded
            $table->tinyInteger('payment_method')->default(1); // 1: cash, 2: stripe
            $table->string('stripe_payment_id')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete(); // Conecta con tu tabla cupons
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('street')->nullable();
            $table->string('outdoor_number')->nullable();
            $table->string('indoor_number')->nullable();
            $table->string('colony')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->boolean('is_gift')->default(false);
            $table->string('gift_message')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
