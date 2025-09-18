<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('provider')->nullable(); // stripe, vnpay, momo
            $table->string('provider_payment_id')->nullable()->unique();
            $table->decimal('amount', 14, 2);
            $table->string('currency', 10)->default('VND');
            $table->string('status')->default('pending'); // pending, succeeded, failed, refunded
            $table->json('metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['order_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
