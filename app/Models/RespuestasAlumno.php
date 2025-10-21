<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RespuestasAlumno extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'respuestas_alumno';
    protected $primaryKey = 'pk_respuesta';

    protected $fillable = [
        'fk_pregunta',
        'fk_alumno',
        'fk_actividad',
        'respuesta',
        'es_correcta',
        'calificada'
    ];

    public function pregunta(){
        return $this->belongsTo(Preguntas::class, 'fk_pregunta');
    }

    public function alumno(){
        return $this->belongsTo(Alumno::class, 'fk_alumno');
    }

    public function actividad(){
        return $this->belongsTo(Actividades::class, 'fk_actividad');
    }
}
