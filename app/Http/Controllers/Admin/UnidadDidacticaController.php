<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnidadDidactica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UnidadDidacticaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.unidad-didactica.index')->only(['index']);
        $this->middleware('permission:admin.unidad-didactica.store')->only(['store', 'create']);
        $this->middleware('permission:admin.unidad-didactica.update')->only(['update', 'edit']);
        $this->middleware('permission:admin.unidad-didactica.destroy')->only(['destroy']);
        $this->middleware('permission:admin.unidad-didactica.restore')->only(['restore']);
    }

    public function index()
    {
        return view('admin.unidad-didactica.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programa_estudio_id' => 'required|exists:programas_estudio,id',
            'nombre' => 'required|string|max:255',
            'creditos' => 'required|integer|min:1',
            'horas_semanales' => 'required|integer|min:1',
        ]);

        try {
            $validator->validate();

            UnidadDidactica::create([
                'programa_estudio_id' => $request->programa_estudio_id,
                'nombre' => $request->nombre,
                'creditos' => $request->creditos,
                'horas_semanales' => $request->horas_semanales,
                'estado' => 'activo',
            ]);

            return redirect()->route('admin.unidad-didactica.index')
                ->with('success', 'La unidad didáctica fue registrada correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'programa_estudio_id' => 'required|exists:programas_estudio,id',
            'nombre' => 'required|string|max:255',
            'creditos' => 'required|integer|min:1',
            'horas_semanales' => 'required|integer|min:1',
        ]);

        try {
            $validator->validate();

            $unidad = UnidadDidactica::findOrFail($id);
            $unidad->update([
                'programa_estudio_id' => $request->programa_estudio_id,
                'nombre' => $request->nombre,
                'creditos' => $request->creditos,
                'horas_semanales' => $request->horas_semanales,
            ]);

            return redirect()->route('admin.unidad-didactica.index')
                ->with('success', 'La unidad didáctica fue actualizada correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $unidad = UnidadDidactica::findOrFail($id);
        $unidad->update(['estado' => 'inactivo']);

        return redirect()->route('admin.unidad-didactica.index')
            ->with('success', 'La unidad didáctica fue desactivada correctamente.');
    }

    public function restore(string $id)
    {
        $unidad = UnidadDidactica::findOrFail($id);
        $unidad->update(['estado' => 'activo']);

        return redirect()->route('admin.unidad-didactica.index')
            ->with('success', 'La unidad didáctica fue restaurada correctamente.');
    }
}
