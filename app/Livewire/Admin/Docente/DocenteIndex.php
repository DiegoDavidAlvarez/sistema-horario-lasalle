<?php

namespace App\Livewire\Admin\Docente;

use App\Models\Docente;
use Livewire\Component;
use Livewire\WithPagination;

class DocenteIndex extends Component
{
    use WithPagination;

    public $estadoFilter = 'activo';

    public function updatedEstadoFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Docente::orderBy('apellidos');

        if ($this->estadoFilter !== 'todos') {
            $query->where('estado', $this->estadoFilter);
        }

        $docentes = $query->paginate(10);

        return view('livewire.admin.docente.docente-index', compact('docentes'));
    }
}