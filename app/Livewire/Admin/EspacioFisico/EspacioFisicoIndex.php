<?php

namespace App\Livewire\Admin\EspacioFisico;

use App\Models\EspacioFisico;
use Livewire\Component;
use Livewire\WithPagination;

class EspacioFisicoIndex extends Component
{
    use WithPagination;

    public $estadoFilter = 'activo';

    public function updatedEstadoFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = EspacioFisico::orderBy('nombre');

        if ($this->estadoFilter !== 'todos') {
            $query->where('estado', $this->estadoFilter);
        }

        $espacios = $query->paginate(12);

        return view('livewire.admin.espacio-fisico.espacio-fisico-index', compact('espacios'));
    }
}
