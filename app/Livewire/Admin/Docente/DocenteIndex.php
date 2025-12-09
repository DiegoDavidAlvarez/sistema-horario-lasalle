<?php

namespace App\Livewire\Admin\Docente;

use App\Models\Docente;
use Livewire\Component;
use Livewire\WithPagination;

class DocenteIndex extends Component
{
    public function render()
    {
        $docentes = Docente::orderBy('apellidos')
            ->paginate(10);

        return view('livewire.admin.docente.docente-index', compact('docentes'));
    }
}