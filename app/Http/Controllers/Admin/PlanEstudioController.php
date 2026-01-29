<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanEstudios;
use App\Models\ProgramaEstudio;
use App\Models\Modulo;
use App\Models\Semestre;
use App\Models\UnidadDidactica;
use Illuminate\Http\Request;

class PlanEstudioController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.plan-estudio.index')->only(['index']);
        $this->middleware('permission:admin.plan-estudio.store')->only(['store', 'create']);
        $this->middleware('permission:admin.plan-estudio.update')->only(['update', 'edit']);
        $this->middleware('permission:admin.plan-estudio.destroy')->only(['destroy']);
        $this->middleware('permission:admin.plan-estudio.restore')->only(['restore']);
    }

    /**
     * Mostrar el listado del plan de estudios
     */
    public function index()
    {
        return view('admin.plan-estudio.index');
    }

    /**
     * Almacenar una nueva asignación en el plan de estudios
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            'unidad_didactica_id' => 'required|exists:unidad_didacticas,id',
            'semestre_id' => 'required|exists:semestres,id',
        ], [
            'modulo_id.required' => 'Debes seleccionar un módulo',
            'modulo_id.exists' => 'El módulo seleccionado no existe',
            'unidad_didactica_id.required' => 'Debes seleccionar una unidad didáctica',
            'unidad_didactica_id.exists' => 'La unidad didáctica seleccionada no existe',
            'semestre_id.required' => 'Debes seleccionar un semestre',
            'semestre_id.exists' => 'El semestre seleccionado no existe',
        ]);

        // Verificar que la unidad didáctica pertenece al mismo programa que el módulo
        $modulo = Modulo::findOrFail($validated['modulo_id']);
        $unidadDidactica = UnidadDidactica::findOrFail($validated['unidad_didactica_id']);

        if ($modulo->programa_estudio_id !== $unidadDidactica->programa_estudio_id) {
            return redirect()->back()
                ->withErrors(['unidad_didactica_id' => 'La unidad didáctica debe pertenecer al mismo programa que el módulo'])
                ->withInput();
        }

        // Verificar si ya existe esta combinación
        $asignacionExistente = PlanEstudios::where('modulo_id', $validated['modulo_id'])
            ->where('unidad_didactica_id', $validated['unidad_didactica_id'])
            ->where('semestre_id', $validated['semestre_id'])
            ->first();

        if ($asignacionExistente) {
            if ($asignacionExistente->estado === 'inactivo') {
                // Reactivar
                $asignacionExistente->update(['estado' => 'activo']);
                
                return redirect()->route('admin.plan-estudio.index')
                    ->with('success', 'La asignación existía previamente como inactiva y ha sido reactivada correctamente');
            } else {
                // Ya existe y está activo
                return redirect()->back()
                    ->withErrors(['general' => 'Esta combinación de módulo, unidad didáctica y semestre ya existe en el plan de estudios'])
                    ->withInput();
            }
        }

        PlanEstudios::create($validated);

        return redirect()->route('admin.plan-estudio.index')
            ->with('success', 'Asignación agregada correctamente al plan de estudios');
    }

    /**
     * Actualizar una asignación existente en el plan de estudios
     */
    public function update(Request $request, $id)
    {
        $planEstudio = PlanEstudios::findOrFail($id);

        $validated = $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            'unidad_didactica_id' => 'required|exists:unidad_didacticas,id',
            'semestre_id' => 'required|exists:semestres,id',
        ], [
            'modulo_id.required' => 'Debes seleccionar un módulo',
            'modulo_id.exists' => 'El módulo seleccionado no existe',
            'unidad_didactica_id.required' => 'Debes seleccionar una unidad didáctica',
            'unidad_didactica_id.exists' => 'La unidad didáctica seleccionada no existe',
            'semestre_id.required' => 'Debes seleccionar un semestre',
            'semestre_id.exists' => 'El semestre seleccionado no existe',
        ]);

        // Verificar que la unidad didáctica pertenece al mismo programa que el módulo
        $modulo = Modulo::findOrFail($validated['modulo_id']);
        $unidadDidactica = UnidadDidactica::findOrFail($validated['unidad_didactica_id']);

        if ($modulo->programa_estudio_id !== $unidadDidactica->programa_estudio_id) {
            return redirect()->back()
                ->withErrors(['unidad_didactica_id' => 'La unidad didáctica debe pertenecer al mismo programa que el módulo'])
                ->withInput();
        }

        // Verificar que no exista ya esta combinación (excluyendo el registro actual)
        $existe = PlanEstudios::where('modulo_id', $validated['modulo_id'])
            ->where('unidad_didactica_id', $validated['unidad_didactica_id'])
            ->where('semestre_id', $validated['semestre_id'])
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withErrors(['general' => 'Esta combinación de módulo, unidad didáctica y semestre ya existe en el plan de estudios'])
                ->withInput();
        }

        $planEstudio->update($validated);

        return redirect()->route('admin.plan-estudio.index')
            ->with('success', 'Asignación actualizada correctamente');
    }

    /**
     * Eliminar una asignación del plan de estudios
     */
    public function destroy($id)
    {
        $planEstudio = PlanEstudios::findOrFail($id);
        $planEstudio->delete();

        return redirect()->route('admin.plan-estudio.index')
            ->with('success', 'Asignación eliminada del plan de estudios');
    }

    /**
     * Restaurar una asignación eliminada (soft delete)
     */
    public function restore($id)
    {
        $planEstudio = PlanEstudios::findOrFail($id);
        $planEstudio->update(['estado' => 'activo']);

        return redirect()->route('admin.plan-estudio.index')
            ->with('success', 'Asignación restaurada correctamente');
    }
}
