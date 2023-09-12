<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('convictions', function (Blueprint $table) {
            $table->id('conviction_id');
            $table->unsignedBigInteger('emp_data_id');
            $table->text('description');

            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data')->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('convictions');
    }
};
