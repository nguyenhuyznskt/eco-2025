<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('shipping_fee', 14, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->string('currency', 10)->default('VND');
            $table->string('status')->default('pending'); // pending, paid, cancelled, completed
            $table->json('billing_address')->nullable();
            $table->json('shipping_address')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['user_id','order_number','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
