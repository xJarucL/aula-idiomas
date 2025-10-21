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
        Schema::create('entrega_pdf_alumno', function (Blueprint $table) {
            $table->id('pk_entrega');
            $table->unsignedBigInteger('fk_actividad');
            $table->unsignedBigInteger('fk_alumno');
            $table->string('archivo_alumno', 255);
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fk_actividad')->references('pk_actividad')->on('actividades')->onDelete('cascade');
            $table->foreign('fk_alumno')->references('pk_alumno')->on('alumno')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega_pdf_alumno');
    }
};
