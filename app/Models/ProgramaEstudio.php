<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaEstudio extends Model
{
    use HasFactory;

    protected $table = 'programas_estudio';

    protected $fillable = [
        'nombre',
        'abreviatura',
        'estado'
    ];

    // Relación con módulos
    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'programa_estudio_id');
    }

    public function unidadesDidacticas()
    {
        return $this->hasMany(UnidadDidactica::class, 'programa_estudio_id');
    }
}