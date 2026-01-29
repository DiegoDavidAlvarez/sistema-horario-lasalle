<?php

namespace App\Livewire\Admin\PlanEstudio;

use App\Models\ProgramaEstudio;
use App\Models\Modulo;
use App\Models\UnidadDidactica;
use App\Models\Semestre;
use Livewire\Component;

class PlanEstudioForm extends Component
{
    public $programa_estudio_id = '';
    public $modulo_id = '';
    public $unidad_didactica_id = '';
    public $semestre_id = '';
    
    // Colecciones para los selects
    public $programasEstudio = [];
    public $modulos = [];
    public $unidadesDidacticas = [];
    public $semestres = [];

    public function mount()
    {
        // Cargar programas de estudio y semestres al inicio
        $this->programasEstudio = ProgramaEstudio::where('estado', 'activo')
            ->orderBy('nombre')
            ->get();
            
        $this->semestres = Semestre::orderBy('numero')->get();
    }

    /**
     * Se ejecuta cuando cambia el programa de estudio seleccionado
     */
    public function updatedProgramaEstudioId($value)
    {
        // Resetear las selecciones dependientes
        $this->modulo_id = '';
        $this->unidad_didactica_id = '';
        
        if ($value) {
            // Cargar módulos del programa seleccionado
            $this->modulos = Modulo::where('programa_estudio_id', $value)
                ->orderBy('numero_modulo')
                ->get();
                
            // Cargar unidades didácticas del programa seleccionado
            $this->unidadesDidacticas = UnidadDidactica::where('programa_estudio_id', $value)
                ->where('estado', 'activo')
                ->orderBy('nombre')
                ->get();
        } else {
            // Si no hay programa seleccionado, vaciar las colecciones
            $this->modulos = [];
            $this->unidadesDidacticas = [];
        }
    }

    public function render()
    {
        return view('livewire.admin.plan-estudio.plan-estudio-form');
    }
}
