<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->unsignedBigInteger('user_type_id');
            $table->string('email', 50)->unique();
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('user_type_id')->references('user_type_id')->on('user_types');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
