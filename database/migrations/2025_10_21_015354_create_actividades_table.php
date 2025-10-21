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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id('pk_actividad');
            $table->string('cod_actividad', 50)->unique();
            $table->string('nom_actividad', 150);
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['preguntas', 'pdf', 'auditiva']);
            $table->unsignedBigInteger('fk_docente');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fk_docente')->references('pk_usuario')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
