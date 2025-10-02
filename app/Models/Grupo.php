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

    public function carrera(){
        return $this->belongsTo(Carrera::class, 'fk_carrera', 'pk_carrera');
    }

    public function cuatrimestre(){
        return $this->belongsTo(Cuatrimestre::class, 'fk_cuatrimestre', 'pk_cuatrimestre');
    }
}
