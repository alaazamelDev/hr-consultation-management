<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('relatives', function (Blueprint $table) {
            $table->id('relative_id');
            $table->unsignedBigInteger('emp_data_id');

            // emp_data id of the relative employee
            $table->unsignedBigInteger('relative_data_id');


            $table->timestamps();

            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data')->onDelete('cascade');
            $table->foreign('relative_data_id')->references('emp_data_id')->on('emp_data')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relatives');
    }
};
