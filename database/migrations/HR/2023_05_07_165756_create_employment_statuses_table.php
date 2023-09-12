<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employment_statuses', function (Blueprint $table) {
            $table->id('emp_status_id');
            $table->string('name', 25);
            $table->string('description', 255)->nullable();

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('employment_statuses');
    }
};
