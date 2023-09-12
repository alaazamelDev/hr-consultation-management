<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_courses', function (Blueprint $table) {
            $table->id('training_course_id');
            $table->unsignedBigInteger('emp_data_id');
            $table->string('name', 100);
            $table->string('institute_name', 100);
            $table->string('city', 70);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('specialize', 70);

            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data')->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('training_courses');
    }
};
