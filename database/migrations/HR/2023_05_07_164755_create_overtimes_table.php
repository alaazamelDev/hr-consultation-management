<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id('overtime_id');
            $table->unsignedBigInteger('emp_id');
            $table->time('duration');
            $table->date('overtime_date');

            $table->foreign('emp_id')->references('emp_id')->on('employees')->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtimes');
    }
};
