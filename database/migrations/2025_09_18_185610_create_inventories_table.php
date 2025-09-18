<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->integer('qty_available')->default(0);
            $table->integer('qty_reserved')->default(0);
            $table->integer('qty_sold')->default(0);
           
            // optional link to settings/warehouses
            $table->timestamps();
            $table->unique(['product_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
