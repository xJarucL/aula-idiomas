<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActividadGrupo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'actividad_grupo';
    protected $primaryKey = 'pk_asignacion';

    protected $fillable = [
        'fk_actividad',
        'fk_grupo',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function actividad(){
        return $this->belongsTo(Actividades::class, 'fk_actividad');
    }

    public function grupo(){
        return $this->belongsTo(Grupo::class, 'fk_grupo');
    }
}

