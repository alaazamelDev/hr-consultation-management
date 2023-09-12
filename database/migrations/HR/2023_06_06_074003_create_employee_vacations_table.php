<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('employee_vacations', function (Blueprint $table) {
            $table->id('employee_vacation_id');

            $table->unsignedBigInteger('emp_id');
            $table->date('start_date');
            $table->unsignedInteger('total_days');
            $table->unsignedInteger('remaining_days')->default(0);

            $table->foreign('emp_id')->references('emp_id')->on('employees')->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_vacations');
    }
};
