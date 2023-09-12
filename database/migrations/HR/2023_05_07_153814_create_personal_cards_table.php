<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_cards', function (Blueprint $table) {
            $table->id('personal_card_id');
            $table->string('card_number', 25);
            $table->string('place_of_issue', 50);
            $table->date('date_of_issue');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_cards');
    }
};
