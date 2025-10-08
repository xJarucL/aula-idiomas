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
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id('pk_calificacion');
            $table->unsignedBigInteger('fk_alumno');
            $table->unsignedBigInteger('fk_materia');
            $table->decimal('calificacion', 4, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fk_alumno')->references('pk_alumno')->on('alumno');
            $table->foreign('fk_materia')->references('pk_materia')->on('materia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
