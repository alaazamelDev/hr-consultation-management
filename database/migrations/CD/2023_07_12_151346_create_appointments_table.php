<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domain\Models\CD\AppointmentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_day_id');
            $table->unsignedBigInteger('status_id')->default(AppointmentStatus::STATUS_AVAILABLE);
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->Time('start_time');
            $table->Time('end_time');
            $table->text('cancellation_reason')->nullable();

            $table->foreign('work_day_id')->references('id')->on('work_days')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('appointment_statuses')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
