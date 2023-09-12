<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affected_users', function (Blueprint $table) {
            $table->id('affected_user_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('log_id');

            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('log_id')->references('log_id')->on('logs');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('affected_users');
    }
};
