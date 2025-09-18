<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('reason')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, refunded
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['order_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
