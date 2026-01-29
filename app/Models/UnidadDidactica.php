<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadDidactica extends Model
{
    protected $fillable = [
        'programa_estudio_id',
        'nombre',
        'creditos',
        'horas_semanales',
        'estado',
    ];

    public function programaEstudio()
    {
        return $this->belongsTo(ProgramaEstudio::class, 'programa_estudio_id');
    }

    /**
     * Relación con los planes de estudio
     */
    public function planesEstudio()
    {
        return $this->hasMany(PlanEstudios::class, 'unidad_didactica_id');
    }

}