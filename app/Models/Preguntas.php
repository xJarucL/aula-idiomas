<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Preguntas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'preguntas';
    protected $primaryKey = 'pk_pregunta';

    protected $fillable = [
        'fk_actividad',
        'pregunta',
        'tipo'
    ];

    public function actividad(){
        return $this->belongsTo(Actividades::class, 'fk_actividad');
    }

    public function opciones(){
        return $this->hasMany(OpcionesPregunta::class, 'fk_pregunta');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestasAlumno::class, 'fk_pregunta');
    }
}
