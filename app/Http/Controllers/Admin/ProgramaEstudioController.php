<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramaEstudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProgramaEstudioController extends Controller
{
    public function index()
    {
        return view('admin.programa-estudio.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:programas_estudio,nombre',
            'abreviatura' => 'nullable|string|max:20|unique:programas_estudio,abreviatura',
        ]);

        try {
            $validator->validate();

            ProgramaEstudio::create([
                'nombre' => $request->nombre,
                'abreviatura' => $request->abreviatura,
                'estado' => 'activo',
            ]);

            return redirect()->route('admin.programa-estudio.index')
                ->with('success', 'El programa de estudio fue registrado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:programas_estudio,nombre,' . $id,
            'abreviatura' => 'nullable|string|max:20|unique:programas_estudio,abreviatura,' . $id,
        ]);

        try {
            $validator->validate();

            $programa = ProgramaEstudio::findOrFail($id);
            $programa->update([
                'nombre' => $request->nombre,
                'abreviatura' => $request->abreviatura,
            ]);

            return redirect()->route('admin.programa-estudio.index')
                ->with('success', 'El programa de estudio fue actualizado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $programa = ProgramaEstudio::findOrFail($id);
        $programa->update(['estado' => 'inactivo']);

        return redirect()->route('admin.programa-estudio.index')
            ->with('success', 'El programa de estudio fue desactivado correctamente.');
    }

    public function restore(string $id)
    {
        $programa = ProgramaEstudio::findOrFail($id);
        $programa->update(['estado' => 'activo']);

        return redirect()->route('admin.programa-estudio.index')
            ->with('success', 'El programa de estudio fue restaurado correctamente.');
    }
}