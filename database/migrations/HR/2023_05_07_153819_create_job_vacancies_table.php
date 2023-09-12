<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id('job_vacancy_id');
            $table->unsignedBigInteger('dep_id');
            $table->string('name', 50);
            $table->text('description');
            $table->integer('count');
            $table->unsignedBigInteger('vacancy_status_id');

            $table->foreign('dep_id')->references('dep_id')->on('departments')->onDelete('cascade');
            $table->foreign('vacancy_status_id')->references('vacancy_status_id')->on('vacancy_statuses');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
