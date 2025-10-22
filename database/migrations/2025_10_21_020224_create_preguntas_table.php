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
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id('pk_pregunta');
            $table->unsignedBigInteger('fk_actividad');
            $table->text('pregunta');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['opcion_multiple', 'abierta']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fk_actividad')->references('pk_actividad')->on('actividades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};
