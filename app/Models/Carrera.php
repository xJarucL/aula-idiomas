<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrera extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'carrera';
    protected $primaryKey = 'pk_carrera';

    public function grupos(){
        return $this->hasMany(Grupo::class, 'fk_carrera', 'pk_carrera');
    }
}
