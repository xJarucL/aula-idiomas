<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grupo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grupo';
    protected $primaryKey = 'pk_grupo';

    protected $fillable = [
        'nombre',
        'año',
        'fk_carrera',
        'fk_cuatrimestre'
    ];

    public function carrera(){
        return $this->belongsTo(Carrera::class, 'fk_carrera', 'pk_carrera');
    }

    public function cuatrimestre(){
        return $this->belongsTo(Cuatrimestre::class, 'fk_cuatrimestre', 'pk_cuatrimestre');
    }

    public function actividades(){
        return $this->belongsToMany(Actividades::class, 'actividad_grupo', 'fk_grupo', 'fk_actividad')
                    ->withPivot(['fecha_inicio', 'fecha_fin'])
                    ->withTimestamps();
    }

    public function alumnos(){
        return $this->belongsToMany(
            Alumno::class,
            'grupo_alumno',
            'fk_grupo',
            'fk_alumno'
        )->with('usuario');
    }

}
