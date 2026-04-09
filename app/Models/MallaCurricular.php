<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MallaCurricular extends Model
{
    use HasFactory;

    protected $table = 'mallas_curriculares';
    
    protected $fillable = [
        'modulo_id',
        'unidad_didactica_id',
        'semestre_id'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function unidadDidactica()
    {
        return $this->belongsTo(UnidadDidactica::class, 'unidad_didactica_id');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'semestre_id');
    }
}
