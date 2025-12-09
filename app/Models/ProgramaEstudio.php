<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaEstudio extends Model
{
    use HasFactory;

    // Especificar el nombre exacto de la tabla
    protected $table = 'programas_estudio';

    protected $fillable = [
        'nombre',
        'abreviatura'
    ];
}