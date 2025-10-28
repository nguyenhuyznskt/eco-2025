<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->boolean('is_filterable')->default(false)->after('type')->comment('Hiển thị trong bộ lọc');
            $table->boolean('is_variation')->default(false)->after('is_filterable')->comment('Dùng để tạo biến thể');
        });
    }

    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn(['is_filterable', 'is_variation']);
        });
    }
};
