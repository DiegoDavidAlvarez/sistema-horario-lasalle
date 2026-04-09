<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MallaCurricular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MallaCurricularController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.malla-curricular.index')->only(['index']);
        $this->middleware('permission:admin.malla-curricular.store')->only(['store', 'create']);
        $this->middleware('permission:admin.malla-curricular.update')->only(['update', 'edit']);
        $this->middleware('permission:admin.malla-curricular.destroy')->only(['destroy']);
    }

    public function index()
    {
        return view('admin.malla-curricular.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'modulo_id' => 'required|exists:modulos,id',
            'unidad_didactica_id' => 'nullable|array',
            'unidad_didactica_id.*' => 'exists:unidades_didacticas,id',
            'semestre_id' => 'required|exists:semestres,id',
        ]);

        try {
            $validator->validate();

            $moduloId = $request->modulo_id;
            $semestreId = $request->semestre_id;
            $unidadesSeleccionadas = $request->unidad_didactica_id ?? []; // Manejar como array vacio

            MallaCurricular::where('modulo_id', $moduloId)
                ->where('semestre_id', $semestreId)
                ->whereNotIn('unidad_didactica_id', $unidadesSeleccionadas)
                ->delete();

            // Paso 2: Crear o actualizar (Sincronizar) las unidades seleccionadas
            foreach ($unidadesSeleccionadas as $unidadId) {
                // updateOrCreate busca el registro que coincida con el primer array,
                // si no lo encuentra lo crea; si lo encuentra, lo actualiza con el segundo array.
                MallaCurricular::firstOrCreate(
                    [
                        'modulo_id' => $moduloId,
                        'semestre_id' => $semestreId,
                        'unidad_didactica_id' => $unidadId,
                    ]
                );
            }

            return redirect()->route('admin.malla-curricular.index')
                ->with('success', 'Malla curricular sincronizada y guardada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'modulo_id' => 'required|exists:modulos,id',
            'unidad_didactica_id' => 'required|exists:unidades_didacticas,id',
            'semestre_id' => 'required|exists:semestres,id',
        ]);

        try {
            $validator->validate();

            $malla = MallaCurricular::findOrFail($id);
            $malla->update([
                'modulo_id' => $request->modulo_id,
                'unidad_didactica_id' => $request->unidad_didactica_id,
                'semestre_id' => $request->semestre_id,
            ]);

            return redirect()->route('admin.malla-curricular.index')
                ->with('success', 'Malla curricular actualizada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $malla = MallaCurricular::findOrFail($id);
        $malla->delete();

        return redirect()->route('admin.malla-curricular.index')
            ->with('success', 'Asignación eliminada permanentemente.');
    }


}
