<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\ProgramaEstudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ModuloController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programa_estudio_id' => 'required|exists:programas_estudio,id',
            'numero_modulo' => 'required|integer|min:1',
            'nombre' => 'required|string|max:200',
        ]);

        try {
            $validator->validate();

            // Verificar si ya existe un módulo con ese número para el programa
            $existe = Modulo::where('programa_estudio_id', $request->programa_estudio_id)
                ->where('numero_modulo', $request->numero_modulo)
                ->exists();

            if ($existe) {
                return back()->with('error', 'Ya existe un módulo con ese número en este programa.');
            }

            Modulo::create([
                'programa_estudio_id' => $request->programa_estudio_id,
                'numero_modulo' => $request->numero_modulo,
                'nombre' => $request->nombre,
            ]);

            return redirect()->route('admin.programa-estudio.index')
                ->with('success', 'Módulo agregado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, Modulo $modulo)
    {

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:200',
        ]);

        try {
            $validator->validate();

            $modulo->update([
                'nombre' => $request->nombre,
            ]);

            return redirect()->route('admin.programa-estudio.index')
                ->with('success', 'Módulo actualizado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(Modulo $modulo)
    {
        $programaId = $modulo->programa_estudio_id;
        $numeroEliminado = $modulo->numero_modulo;

        // Eliminar el módulo
        $modulo->delete();

        // Reordenar los módulos restantes
        $modulosRestantes = Modulo::where('programa_estudio_id', $programaId)
            ->where('numero_modulo', '>', $numeroEliminado)
            ->orderBy('numero_modulo', 'asc')
            ->get();

        foreach ($modulosRestantes as $index => $modulo) {
            $modulo->update(['numero_modulo' => $numeroEliminado + $index]);
        }

        return redirect()->route('admin.programa-estudio.index')
            ->with('success', 'Módulo eliminado y reordenado correctamente.');
    }
}