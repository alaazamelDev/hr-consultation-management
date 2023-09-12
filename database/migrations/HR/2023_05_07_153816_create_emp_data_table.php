<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emp_data', function (Blueprint $table) {
            $table->id('emp_data_id');
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->text('personal_photo')->nullable();
            $table->string('father_name', 255);
            $table->string('grand_father_name', 255);
            $table->date('birth_date');
            $table->string('birth_place', 80);
            $table->integer('marital_status');
            $table->date('start_working_date');
            $table->boolean('is_employed');
            $table->unsignedBigInteger('card_id');
            $table->unsignedBigInteger('passport_id')->nullable();
            $table->unsignedBigInteger('driving_licence_id')->unique()->nullable();
            $table->unsignedBigInteger('address_id');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('card_id')->references('personal_card_id')->on('personal_cards');
            $table->foreign('passport_id')->references('passport_id')->on('passports');
            $table->foreign('address_id')->references('address_id')->on('addresses');
            $table->foreign('driving_licence_id')->references('driving_licence_id')->on('driving_licences');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emp_data');
    }
};
