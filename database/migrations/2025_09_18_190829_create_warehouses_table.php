<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // tên kho
            $table->string('code')->unique(); // mã kho
            $table->string('location')->nullable(); // địa chỉ
            $table->string('manager_name')->nullable(); // tên người quản lý
            $table->string('manager_phone')->nullable(); // số điện thoại quản lý
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
