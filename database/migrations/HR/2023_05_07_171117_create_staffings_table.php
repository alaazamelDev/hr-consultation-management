<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staffings', function (Blueprint $table) {
            $table->id('staff_id');
            $table->unsignedBigInteger('emp_id');
            $table->unsignedBigInteger('job_title_id');
            $table->unsignedBigInteger('dep_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->foreign('emp_id')->references('emp_id')->on('employees')->cascadeOnDelete();
            $table->foreign('job_title_id')->references('job_title_id')->on('job_titles')->cascadeOnDelete();
            $table->foreign('dep_id')->references('dep_id')->on('departments')->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staffings');
    }
};
