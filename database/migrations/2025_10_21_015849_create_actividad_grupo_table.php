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
        Schema::create('actividad_grupo', function (Blueprint $table) {
            $table->id('pk_asignacion');
            $table->unsignedBigInteger('fk_actividad');
            $table->unsignedBigInteger('fk_grupo');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fk_actividad')->references('pk_actividad')->on('actividades')->onDelete('cascade');
            $table->foreign('fk_grupo')->references('pk_grupo')->on('grupo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_grupo');
    }
};
