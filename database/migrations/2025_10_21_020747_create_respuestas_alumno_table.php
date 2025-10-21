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
        Schema::create('respuestas_alumno', function (Blueprint $table) {
            $table->id('pk_respuesta');
            $table->unsignedBigInteger('fk_pregunta');
            $table->unsignedBigInteger('fk_alumno');
            $table->unsignedBigInteger('fk_actividad');
            $table->text('respuesta')->nullable();
            $table->boolean('es_correcta')->nullable();
            $table->boolean('calificada')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fk_pregunta')->references('pk_pregunta')->on('preguntas')->onDelete('cascade');
            $table->foreign('fk_alumno')->references('pk_alumno')->on('alumno')->onDelete('cascade');
            $table->foreign('fk_actividad')->references('pk_actividad')->on('actividades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuestas_alumno');
    }
};
