<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('latetimes', function (Blueprint $table) {
            $table->id('latetime_id');
            $table->unsignedBigInteger('emp_id');
            $table->time('duration');
            $table->date('latetime_date');

            $table->foreign('emp_id')->references('emp_id')->on('employees');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('latetimes');
    }
};
