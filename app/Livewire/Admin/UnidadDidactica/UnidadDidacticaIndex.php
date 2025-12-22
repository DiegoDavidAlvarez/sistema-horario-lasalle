<?php

namespace App\Livewire\Admin\UnidadDidactica;

use App\Models\ProgramaEstudio;
use Livewire\Component;
use Livewire\WithPagination;

class UnidadDidacticaIndex extends Component
{
    use WithPagination;

    public $programaEstadoFilter = 'activo';
    public $unidadEstadoFilter = 'activo';

    public function updatedProgramaEstadoFilter()
    {
        $this->resetPage();
    }

    public function updatedUnidadEstadoFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ProgramaEstudio::query();
        
        // Aplicar filtro de estado para programas
        if ($this->programaEstadoFilter !== 'todos') {
            $query->where('estado', $this->programaEstadoFilter);
        }
        
        // Cargar unidades didácticas con su filtro
        $query->with(['unidadesDidacticas' => function ($query) {
            if ($this->unidadEstadoFilter !== 'todos') {
                $query->where('estado', $this->unidadEstadoFilter);
            }
        }])->orderBy('nombre');

        $programas = $query->paginate(10);

        // Obtener todos los programas activos para el select del modal de creación
        $allProgramas = ProgramaEstudio::where('estado', 'activo')->orderBy('nombre')->get();

        return view('livewire.admin.unidad-didactica.unidad-didactica-index', compact('programas', 'allProgramas'));
    }

}
