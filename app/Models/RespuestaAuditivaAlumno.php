<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RespuestaAuditivaAlumno extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'respuesta_auditiva_alumno';
    protected $primaryKey = 'pk_respuesta';

    protected $fillable = [
        'fk_actividad',
        'fk_alumno',
        'archivo_audio_alumno',
        'calificacion',
        'observaciones'
    ];

    public function actividad(){
        return $this->belongsTo(Actividades::class, 'fk_actividad');
    }

    public function alumno(){
        return $this->belongsTo(Alumno::class, 'fk_alumno');
    }
}
