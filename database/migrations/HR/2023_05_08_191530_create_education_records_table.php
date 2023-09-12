<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('education_records', function (Blueprint $table) {
            $table->id('education_record_id');
            $table->unsignedBigInteger('emp_data_id');
            $table->unsignedBigInteger('education_level_id');
            $table->string('univ_name');
            $table->string('city');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('specialize')->nullable();
            $table->double('grade')->nullable();

            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data');
            $table->foreign('education_level_id')->references('education_level_id')->on('education_levels');

            $table->SoftDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education_records');
    }
};
