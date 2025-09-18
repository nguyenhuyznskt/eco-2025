<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('content')->nullable();
            $table->string('status')->default('open'); // open/processing/closed
            $table->integer('priority')->default(3);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['user_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
