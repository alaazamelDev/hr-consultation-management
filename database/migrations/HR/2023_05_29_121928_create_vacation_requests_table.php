<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vacation_requests', function (Blueprint $table) {
            $table->id('vacation_req_id');
            $table->unsignedBigInteger('emp_id');
            $table->Integer('req_stat')->default(1);
            $table->text('description')->nullable();
            $table->datetime('start_date');
            $table->integer('duration');
            $table->timestamps();

            $table->foreign('emp_id')->references('emp_id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacation_requests');
    }
};
