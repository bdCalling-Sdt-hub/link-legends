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
            $table->string('fullName');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string("userType");
            $table->string("image")->nullable();
            $table->string("otp");
            $table->string('contact_no')->nullable();
            $table->string('address')->nullable();
            $table->boolean("verify_email");
            $table->tinyInteger('user_status')->default(0);
            $table->rememberToken()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
