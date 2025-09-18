<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promotion_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->decimal('discount_amount', 14, 2)->nullable();
            $table->timestamps();
            $table->unique(['promotion_id','product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_products');
    }
};
