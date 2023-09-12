<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id('job_app_id');
            $table->unsignedBigInteger('app_status_id');
            $table->unsignedBigInteger('job_vacancy_id');
            $table->unsignedBigInteger('emp_data_id');
            $table->text('section_man_notes')->nullable();
            $table->text('vice_man_rec')->nullable();


            $table->foreign('app_status_id')->references('app_status_id')->on('application_statuses');
            $table->foreign('job_vacancy_id')->references('job_vacancy_id')->on('job_vacancies')->onDelete('cascade');
            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data')->onDelete('cascade');

            $table->SoftDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
