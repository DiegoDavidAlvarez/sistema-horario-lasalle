<?php

namespace App\Livewire\Admin\ProgramaEstudio;

use App\Models\ProgramaEstudio;
use Livewire\Component;

class ProgramaEstudioTable extends Component
{
    public function render()
    {
        $programas = ProgramaEstudio::with([
            'modulos' => function ($query) {
                $query->orderBy('numero_modulo', 'asc');
            }
        ])->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.programa-estudio.programa-estudio-table', compact('programas'));
    }
}