<?php

namespace App\Livewire\Admin\MallaCurricular;

use App\Models\MallaCurricular;
use App\Models\ProgramaEstudio;
use App\Models\Semestre;
use Livewire\Component;
use Livewire\WithPagination;

class MallaCurricularIndex extends Component
{
    use WithPagination;

    public $programaEstadoFilter = 'activo';

    public function updatedProgramaEstadoFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ProgramaEstudio::query();

        // Filtro de estado de programas
        if ($this->programaEstadoFilter !== 'todos') {
            $query->where('estado', $this->programaEstadoFilter);
        }

        // Cargar relaciones necesarias para cada programa
        $query->with([
            'modulos' => function ($q) {
                $q->orderBy('numero_modulo');
            },
            'unidadesDidacticas' => function ($q) {
                $q->where('estado', 'activo');
            },
        ])->orderBy('nombre');

        $programas = $query->paginate(10);

        // Para cada programa, cargar sus mallas curriculares agrupadas por semestre
        $semestres = Semestre::orderBy('numero')->get();

        // Obtener todas las mallas por programa para mostrar en la vista
        $programaIds = $programas->pluck('id')->toArray();
        
        $mallasQuery = MallaCurricular::with(['modulo', 'unidadDidactica', 'semestre'])
            ->whereHas('modulo', function ($q) use ($programaIds) {
                $q->whereIn('programa_estudio_id', $programaIds);
            });

        $mallasPorPrograma = $mallasQuery->get()->groupBy(function ($malla) {
            return $malla->modulo->programa_estudio_id;
        });

        return view('livewire.admin.malla-curricular.malla-curricular-index', compact(
            'programas',
            'semestres',
            'mallasPorPrograma'
        ));
    }
}
