<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('accountable'); // tạo accountable_id, accountable_type + index
            $table->decimal('amount', 12, 2);
            $table->string('type'); // credit, debit
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->text('meta')->nullable();
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
