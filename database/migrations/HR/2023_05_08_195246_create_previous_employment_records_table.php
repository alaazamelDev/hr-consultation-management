<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('previous_employment_records', function (Blueprint $table) {
            $table->id('prev_emp_record_id');
            $table->unsignedBigInteger('emp_data_id');
            $table->string('employer_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('address');
            $table->string('telephone', 25);
            $table->string('job_title', 50);
            $table->text('job_description');
            $table->integer('salary');
            $table->integer('allowance');
            $table->text('quit_reason')->nullable();

            $table->SoftDeletes();
            $table->timestamps();

            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('previous_employment_records');
    }
};
