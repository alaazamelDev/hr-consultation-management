<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('job_title_permissions', function (Blueprint $table) {
            $table->id('job_title_perm_id');
            $table->unsignedBigInteger('job_title_id');
            $table->unsignedBigInteger('perm_id');

            $table->foreign('job_title_id')->references('job_title_id')->on('job_titles')->cascadeOnDelete();
            $table->foreign('perm_id')->references('perm_id')->on('permissions')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_title_permissions');
    }
};
