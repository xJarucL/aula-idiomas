<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumno extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'alumno';
    protected $primaryKey = 'pk_alumno';

    protected $fillable = [
        'fk_usuario',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'fk_usuario')->withTrashed();
    }

    public function grupos(){
        return $this->hasMany(GrupoAlumno::class, 'fk_alumno', 'pk_alumno');
    }

    public function calificaciones(){
        return $this->hasMany(Calificaciones::class, 'fk_alumno', 'pk_alumno');
    }

}
