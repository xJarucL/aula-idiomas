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
        Schema::create('grupo_materia', function (Blueprint $table) {
            $table->id('pk_grupo_materia');
            $table->unsignedBigInteger('fk_materia');
            $table->foreign('fk_materia')->references('pk_materia')->on('materia');
            $table->unsignedBigInteger('fk_grupo');
            $table->foreign('fk_grupo')->references('pk_grupo')->on('grupo');
            $table->unsignedBigInteger('fk_docente');
            $table->foreign('fk_docente')->references('pk_usuario')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_materia');
    }
};
