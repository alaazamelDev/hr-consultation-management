<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id('absence_id');
            $table->unsignedBigInteger('emp_id');
            $table->date('absence_date');
            $table->unsignedBigInteger('absence_status_id')->default(2);

            $table->timestamps();
            //$table->softDeletes();

            $table->foreign('emp_id')->references('emp_id')->on('employees')->cascadeOnDelete();
            $table->foreign('absence_status_id')->references('absence_status_id')->on('absence_statuses');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
