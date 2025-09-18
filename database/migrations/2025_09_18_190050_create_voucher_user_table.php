<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voucher_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('used_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unique(['voucher_id','user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_user');
    }
};
