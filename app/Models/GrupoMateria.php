<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrupoMateria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grupo_materia';
    protected $primaryKey = 'pk_grupo_materia';

    public function grupo(){
        return $this->belongsTo(Grupo::class, 'fk_grupo', 'pk_grupo');
    }

    public function materia(){
        return $this->belongsTo(Materia::class, 'fk_materia', 'pk_materia');
    }

    public function docente(){
        return $this->belongsTo(User::class, 'fk_docente', 'pk_usuario');
    }
}
