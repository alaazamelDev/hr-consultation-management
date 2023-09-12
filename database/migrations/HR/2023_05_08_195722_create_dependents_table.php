<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dependents', function (Blueprint $table) {
            $table->id('dependent_id');
            $table->unsignedBigInteger('emp_data_id');
            $table->string('name', 255);
            $table->integer('age');
            $table->text('relation');
            $table->text('address');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('emp_data_id')->references('emp_data_id')->on('emp_data');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dependents');
    }
};
