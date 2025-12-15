<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres',
        'apellidos',
        'email',
        'tipo_documento',
        'numero_documento',
        'nivel_academico',
        'estado',
    ];

    protected $casts = [
        'numero_documento' => 'string',
    ];
}
