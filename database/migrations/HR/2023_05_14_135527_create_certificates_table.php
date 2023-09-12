<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id('certificate_id');
            $table->unsignedBigInteger('emp_data_id');
            $table->string('name', 50);
            $table->text('file_url');
            $table->timestamps();

            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
