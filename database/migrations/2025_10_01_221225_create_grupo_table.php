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
        Schema::create('grupo', function (Blueprint $table) {
            $table->id('pk_grupo');
            $table->string('nombre');
            $table->string('año');
            $table->unsignedBigInteger('fk_carrera');
            $table->foreign('fk_carrera')->references('pk_carrera')->on('carrera');
            $table->unsignedBigInteger('fk_cuatrimestre');
            $table->foreign('fk_cuatrimestre')->references('pk_cuatrimestre')->on('cuatrimestre');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo');
    }
};
