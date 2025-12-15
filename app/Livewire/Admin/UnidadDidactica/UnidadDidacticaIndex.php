<?php

namespace App\Livewire\Admin\UnidadDidactica;

use App\Models\ProgramaEstudio;
use Livewire\Component;
use Livewire\WithPagination;

class UnidadDidacticaIndex extends Component
{
    use WithPagination;

    public $estadoFilter = 'activo';

    public function updatedEstadoFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ProgramaEstudio::with(['unidadesDidacticas' => function ($query) {
                if ($this->estadoFilter !== 'todos') {
                    $query->where('estado', $this->estadoFilter);
                }
            }])
            ->orderBy('nombre');

        $programas = $query->paginate(10);

        // Obtener todos los programas activos para el select del modal de creación
        $allProgramas = ProgramaEstudio::where('estado', 'activo')->orderBy('nombre')->get();

        return view('livewire.admin.unidad-didactica.unidad-didactica-index', compact('programas', 'allProgramas'));
    }
}
