<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->decimal('amount', 14, 2);
            $table->decimal('fee', 14, 2)->default(0);
            $table->string('status')->default('pending'); // pending, processing, paid, failed
            $table->dateTime('paid_at')->nullable();
            $table->string('provider')->nullable(); // transfer provider
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['vendor_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
