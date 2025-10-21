<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActividadPDF extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'actividad_pdf';
    protected $primaryKey = 'pk_pdf';

    protected $fillable = [
        'fk_actividad',
        'archivo_docente'
    ];

    public function actividad(){
        return $this->belongsTo(Actividades::class, 'fk_actividad');
    }
}
