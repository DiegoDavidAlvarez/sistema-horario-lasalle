<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspacioFisico extends Model
{
    use HasFactory;

    protected $table = 'espacios_fisicos';

    protected $fillable = [
        'nombre',
        'estado'
    ];

    public function horario()
    {
        return $this->hasMany(Horario::class);
    }
}
