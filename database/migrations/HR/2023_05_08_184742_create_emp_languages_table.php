<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emp_languages', function (Blueprint $table) {
            $table->id('emp_lang_id');
            $table->unsignedBigInteger('emp_data_id');
            $table->unsignedBigInteger('language_id');
            $table->integer('speaking_level');
            $table->integer('writing_level');
            $table->integer('reading_level');

            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data')->cascadeOnDelete();
            $table->foreign('language_id')->references('language_id')->on('languages')->cascadeOnDelete();

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('emp_languages');
    }
};
