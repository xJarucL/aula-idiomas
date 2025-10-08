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
}
