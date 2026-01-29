<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanEstudios extends Model
{
    use HasFactory;

    protected $table = 'plan_estudios';

    protected $fillable = [
        'modulo_id',
        'unidad_didactica_id',
        'semestre_id',
        'estado'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function unidadDidactica()
    {
        return $this->belongsTo(UnidadDidactica::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
}
