<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_shop_id')->constrained('order_shops')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('sku')->nullable();
            $table->integer('qty')->unsigned();
            $table->decimal('unit_price', 14, 2);
            $table->decimal('total_price', 14, 2);
            $table->string('status')->default('ordered'); // ordered, shipped, delivered, refunded
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['order_shop_id','product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
