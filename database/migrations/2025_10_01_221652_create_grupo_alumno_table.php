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
        Schema::create('grupo_alumno', function (Blueprint $table) {
            $table->id('pk_grupo_alumno');
            $table->unsignedBigInteger('fk_alumno');
            $table->foreign('fk_alumno')->references('pk_alumno')->on('alumno');
            $table->unsignedBigInteger('fk_grupo');
            $table->foreign('fk_grupo')->references('pk_grupo')->on('grupo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_alumno');
    }
};
