<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpcionesPregunta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'opciones_pregunta';
    protected $primaryKey = 'pk_opcion';

    protected $fillable = [
        'fk_pregunta',
        'texto_opcion',
        'es_correcta'
    ];

    public function pregunta(){
        return $this->belongsTo(Preguntas::class, 'fk_pregunta');
    }
}
