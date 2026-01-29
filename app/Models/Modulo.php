<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'programa_estudio_id',
        'numero_modulo',
        'nombre',
        'codigo',
        'semestre',
    ];
    /**
     * Relación con el programa de estudio
     */
    public function programaEstudio()
    {
        return $this->belongsTo(ProgramaEstudio::class, 'programa_estudio_id');
    }

    /**
     * Relación con los planes de estudio (1:N)
     */
    public function planesEstudio()
    {
        return $this->hasMany(PlanEstudios::class, 'modulo_id');
    }
}
