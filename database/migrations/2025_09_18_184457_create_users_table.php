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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // identity
            $table->string('username')->nullable()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // auth
            $table->string('password')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();

            // contact / profile
            $table->string('phone')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->enum('gender', ['male','female','other'])->nullable();
            $table->date('dob')->nullable();
            $table->text('bio')->nullable();
            $table->string('address')->nullable();

            $table->string('locale', 10)->default('vi');
            $table->json('meta')->nullable();

            // role & status
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['active','inactive','banned'])->default('active');

            // security
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();

            // audit
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();

            // soft deletes + timestamps
            $table->softDeletes();
            $table->timestamps();

            // indexes
            $table->index(['role_id']);
            $table->index(['last_login_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
    
};
