<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id('passport_id');
            $table->string('passport_number', 25);
            $table->string('place_of_issue', 50);
            $table->date('date_of_issue');
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
