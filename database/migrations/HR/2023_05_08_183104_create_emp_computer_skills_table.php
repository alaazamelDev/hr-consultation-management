<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emp_computer_skills', function (Blueprint $table) {
            $table->id('emp_com_skill_id');
            $table->unsignedBigInteger('emp_data_id');
            $table->unsignedBigInteger('computer_skill_id');
            $table->integer('level');

            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data')->cascadeOnDelete();
            $table->foreign('computer_skill_id')->references('computer_skill_id')->on('computer_skills')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emp_computer_skills');
    }
};
