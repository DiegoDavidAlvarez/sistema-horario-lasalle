<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueTiempo extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_bloque',
        'hora_inicio',
        'hora_fin',
        'duracion_minutos',
        'es_receso'
    ];

    protected $casts = [
        'es_receso' => 'boolean',
    ];
}
