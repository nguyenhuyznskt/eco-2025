<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->default(5); // 1-5
            $table->text('title')->nullable();
            $table->longText('body')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            $table->index(['product_id','rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
