<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cuatrimestre extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cuatrimestre';
    protected $primaryKey = 'pk_cuatrimestre';

    public function grupos(){
        return $this->hasMany(Grupo::class, 'fk_carrera', 'pk_carrera');
    }
}
