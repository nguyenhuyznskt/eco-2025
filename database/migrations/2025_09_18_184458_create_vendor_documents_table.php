<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('document_type');
            $table->string('document_path');
            $table->string('file_name')->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
            $table->index(['vendor_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_documents');
    }
    
};
