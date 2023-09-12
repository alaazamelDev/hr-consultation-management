<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_statuses', function (Blueprint $table) {
            $table->id('app_status_id');
            $table->string('name', 50);
            $table->string('description', 255);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_statuses');
    }
};
