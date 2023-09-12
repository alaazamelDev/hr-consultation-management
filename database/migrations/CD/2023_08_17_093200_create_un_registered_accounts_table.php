<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('un_registered_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id'); // FK
            $table->string('name', 25);
            $table->string('phone_number', 12);

            $table->foreign('app_id')->references('id')->on('appointments');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('un_registered_accounts');
    }
};
