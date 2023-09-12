<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('references', function (Blueprint $table) {
            $table->id('reference_id');
            $table->unsignedBigInteger('emp_data_id');
            $table->string('name', 70);
            $table->string('job', 70);
            $table->string('company', 70);
            $table->string('telephone', 25);
            $table->text('address');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
