<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('requested'); // requested, approved, rejected, received
            $table->string('reason')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['order_item_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
