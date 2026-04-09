<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EspacioFisico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EspacioFisicoController extends Controller
{
    public function index()
    {
        return view('admin.espacio-fisico.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ]);

        try {
            $validator->validate();

            EspacioFisico::create([
                'nombre' => $request->nombre,
                'estado' => 'activo',
            ]);

            return redirect()->route('admin.espacio-fisico.index')
                ->with('success', 'El espacio fue registrado correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ]);

        try {
            $validator->validate();

            $espacio = EspacioFisico::findOrFail($id);

            $espacio->update([
                'nombre' => $request->nombre,
            ]);

            return redirect()->route('admin.espacio-fisico.index')
                ->with('success', 'El espacio fue actualizado correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $espacio = EspacioFisico::findOrFail($id);

        $espacio->update([
            'estado' => 'inactivo',
        ]);

        return redirect()->route('admin.espacio-fisico.index')
            ->with('success', 'El espacio fue eliminado correctamente');
    }

    public function restore(string $id)
    {
        $espacio = EspacioFisico::findOrFail($id);
        $espacio->update(['estado' => 'activo']);

        return redirect()->route('admin.espacio-fisico.index')
            ->with('success', 'El espacio fue restaurado correctamente.');
    }
}
