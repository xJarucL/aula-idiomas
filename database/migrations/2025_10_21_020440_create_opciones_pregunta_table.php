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
        Schema::create('opciones_pregunta', function (Blueprint $table) {
            $table->id('pk_opcion');
            $table->unsignedBigInteger('fk_pregunta');
            $table->text('texto_opcion');
            $table->boolean('es_correcta')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fk_pregunta')->references('pk_pregunta')->on('preguntas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opciones_pregunta');
    }
};
