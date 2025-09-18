<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_shop_id')->constrained('order_shops')->onDelete('cascade');
            $table->string('provider')->nullable();
            $table->string('tracking_number')->nullable()->unique();
            $table->string('status')->default('created'); // created,in_transit,delivered,returned
            $table->decimal('shipping_cost', 14, 2)->default(0);
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['order_shop_id','tracking_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
