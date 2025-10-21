<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EntregaPDFAlumno extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'entrega_pdf_alumno';
    protected $primaryKey = 'pk_entrega';

    protected $fillable = [
        'fk_actividad',
        'fk_alumno',
        'archivo_alumno',
        'calificacion',
        'observaciones',
        'fecha_entrega'
    ];

    public function actividad(){
        return $this->belongsTo(Actividades::class, 'fk_actividad');
    }

    public function alumno(){
        return $this->belongsTo(Alumno::class, 'fk_alumno');
    }
}
