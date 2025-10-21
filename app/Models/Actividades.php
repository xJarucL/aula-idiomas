<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Actividades extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'actividades';
    protected $primaryKey = 'pk_actividad';

    protected $fillable = [
        'cod_actividad',
        'nom_actividad',
        'descripcion',
        'tipo',
        'fk_docente'
    ];

    public function docente(){
        return $this->belongsTo(User::class, 'fk_docente');
    }

    public function grupos(){
        return $this->belongsToMany(Grupo::class, 'actividad_grupo', 'fk_actividad', 'fk_grupo')
                    ->withPivot(['fecha_inicio', 'fecha_fin'])
                    ->withTimestamps();
    }

    public function preguntas(){
        return $this->hasMany(Preguntas::class, 'fk_actividad');
    }

    public function actividadPdf(){
        return $this->hasOne(ActividadPDF::class, 'fk_actividad');
    }

    public function frasesAuditivas(){
        return $this->hasMany(ActividadAuditivaFrases::class, 'fk_actividad');
    }

    public function respuestas(){
        return $this->hasMany(RespuestasAlumno::class, 'fk_actividad');
    }

}
