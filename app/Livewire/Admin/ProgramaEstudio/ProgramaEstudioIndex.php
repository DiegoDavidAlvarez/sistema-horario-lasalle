<?php

namespace App\Livewire\Admin\ProgramaEstudio;

use App\Models\ProgramaEstudio;
use Livewire\Component;
use Livewire\WithPagination;

class ProgramaEstudioIndex extends Component
{
    use WithPagination;

    public $estadoFilter = 'activo';

    public function updatedEstadoFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ProgramaEstudio::with([
            'modulos' => function ($query) {
                $query->orderBy('numero_modulo', 'asc');
            }
        ])->orderBy('created_at', 'desc');

        if ($this->estadoFilter !== 'todos') {
            $query->where('estado', $this->estadoFilter);
        }

        $programas = $query->paginate(10);

        return view('livewire.admin.programa-estudio.programa-estudio-index', compact('programas'));
    }
}