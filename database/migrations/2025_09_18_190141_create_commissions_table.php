<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->enum('type', ['percent','fixed'])->default('percent');
            $table->decimal('value', 8, 2)->default(0); // percent or fixed amount
            $table->string('scope')->nullable(); // global/category/product
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['vendor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
