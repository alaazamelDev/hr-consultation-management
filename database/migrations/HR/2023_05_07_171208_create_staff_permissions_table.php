<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_permissions', function (Blueprint $table) {
            $table->id('staff_perm_id');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('perm_id');
            $table->tinyInteger('status');

            $table->foreign('staff_id')->references('staff_id')->on('staffings')->cascadeOnDelete();
            $table->foreign('perm_id')->references('perm_id')->on('permissions')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_permissions');
    }
};
