<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed','percent'])->default('percent');
            $table->decimal('value', 14, 2);
            $table->decimal('max_value', 14, 2)->nullable(); // cap for percent
            $table->decimal('min_order_amount', 14, 2)->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->integer('usage_limit_global')->nullable();
            $table->integer('usage_limit_per_user')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['code','is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
