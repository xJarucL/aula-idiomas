<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActividadAuditivaFrases extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'actividad_auditiva_frases';
    protected $primaryKey = 'pk_frase';

    protected $fillable = [
        'fk_actividad',
        'texto_frase',
        'archivo_audio_docente'
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividades::class, 'fk_actividad');
    }
}
