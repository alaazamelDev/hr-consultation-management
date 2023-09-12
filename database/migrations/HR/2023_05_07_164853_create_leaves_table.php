<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id('leave_id');
            $table->unsignedBigInteger('emp_id');
            $table->unsignedBigInteger('shift_req_id')->nullable();
            $table->unsignedInteger('uid')->default(0);
            $table->tinyInteger('state')->default(0);
            $table->time('leave_time');
            $table->date('leave_date');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('type')->default(1);

            $table->time('schedule_time_in')->nullable();
            $table->time('schedule_time_out')->nullable();

            $table->foreign('emp_id')->references('emp_id')->on('employees')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
