<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->morphs('owner'); // owner_type, owner_id -> user/vendor
            $table->decimal('balance', 14, 2)->default(0);
            $table->decimal('hold', 14, 2)->default(0); // reserved
            $table->string('currency', 10)->default('VND');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unique(['owner_type','owner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
