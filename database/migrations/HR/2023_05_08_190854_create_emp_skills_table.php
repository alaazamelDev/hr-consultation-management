<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emp_skills', function (Blueprint $table) {
            $table->id('emp_skill_id');
            $table->unsignedBigInteger('skill_id');
            $table->unsignedBigInteger('emp_data_id');

            $table->foreign('skill_id')->references('skill_id')->on('skills');
            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data');

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('emp_skills');
    }
};
