<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calificaciones extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'calificaciones';
    protected $primaryKey = 'pk_calificacion';

    protected $fillable = [
        'fk_alumno',
        'fk_materia',
        'calificacion'
    ];

    public function alumno() {
        return $this->belongsTo(Alumno::class, 'fk_alumno', 'pk_alumno');
    }

}
