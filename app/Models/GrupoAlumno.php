<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrupoAlumno extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grupo_alumno';
    protected $primaryKey = 'pk_grupo_alumno';

    protected $fillable = [
        'fk_alumno',
        'fk_grupo',
    ];

    public function alumno(){
        return $this->belongsTo(Alumno::class, 'fk_alumno', 'pk_alumno');
    }

    public function grupo(){
        return $this->belongsTo(Grupo::class, 'fk_grupo', 'pk_grupo');
    }


}
