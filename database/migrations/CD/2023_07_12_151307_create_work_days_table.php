<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shift_id');
//            $table->unsignedBigInteger('day_id');
            $table->date('day_date');

            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
//            $table->foreign('day_id')->references('working_day_id')->on('working_days')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_days');
    }
};
