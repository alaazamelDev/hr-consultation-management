<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('address_id');
            $table->string('state', 50);
            $table->string('city', 50);
            $table->string('street', 70);
            $table->string('home_phone_no', 25)->nullable();
            $table->string('work_phone_no', 25)->nullable();
            $table->string('mobile_no', 25)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
