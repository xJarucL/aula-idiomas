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
        Schema::create('actividad_pdf', function (Blueprint $table) {
            $table->id('pk_pdf');
            $table->unsignedBigInteger('fk_actividad');
            $table->string('archivo_docente', 255);
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
        Schema::dropIfExists('actividad_pdf');
    }
};
