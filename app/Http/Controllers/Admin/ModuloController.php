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
    public function __construct()
    {
        $this->middleware('permission:admin.modulos.store')->only(['store', 'create']);
        $this->middleware('permission:admin.modulos.update')->only(['update', 'edit']);
        $this->middleware('permission:admin.modulos.destroy')->only(['destroy']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programa_estudio_id' => 'required|exists:programas_estudio,id',
            'numero_modulo' => 'required|integer|min:1|max:3',
            'nombre' => 'required|string|max:200',
        ], [
            'numero_modulo.min' => 'El número de módulo debe ser al menos 1.',
            'numero_modulo.max' => 'El número de módulo no puede ser mayor a 3.',
            'numero_modulo.required' => 'Debe seleccionar un número de módulo.',
        ]);

        try {
            $validator->validate();

            // Verificar si ya existe un módulo con ese número para el programa
            $existe = Modulo::where('programa_estudio_id', $request->programa_estudio_id)
                ->where('numero_modulo', $request->numero_modulo)
                ->exists();

            if ($existe) {
                return back()
                    ->withErrors(['numero_modulo' => 'El módulo ' . $request->numero_modulo . ' ya está registrado para este programa de estudios.'])
                    ->withInput();
            }

            Modulo::create([
                'programa_estudio_id' => $request->programa_estudio_id,
                'numero_modulo' => $request->numero_modulo,
                'nombre' => $request->nombre,
            ]);

            return redirect()->route('admin.programa-estudio.index')
                ->with('success', 'Módulo ' . $request->numero_modulo . ' agregado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:200',
            'numero_modulo' => 'required|integer|min:1|max:3',
        ]);

        try {
            $validator->validate();

            $modulo = Modulo::findOrFail($id);

            // Verificar si el nuevo número de módulo ya está en uso por otro módulo del mismo programa
            $existe = Modulo::where('programa_estudio_id', $modulo->programa_estudio_id)
                ->where('numero_modulo', $request->numero_modulo)
                ->where('id', '!=', $id)
                ->exists();

            if ($existe) {
                return back()
                    ->withErrors(['numero_modulo' => 'El módulo ' . $request->numero_modulo . ' ya está registrado para este programa de estudios.'])
                    ->withInput();
            }

            Modulo::where('id', $id)->update([
                'nombre' => $request->nombre,
                'numero_modulo' => $request->numero_modulo,
            ]);

            return redirect()->route('admin.programa-estudio.index')
                ->with('success', 'Módulo actualizado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $modulo = Modulo::findOrFail($id);
        $programaId = $modulo->programa_estudio_id;
        $numeroEliminado = $modulo->numero_modulo;

        // Eliminar el módulo
        $modulo->delete();

        // Reordenar los módulos restantes
        $modulosRestantes = Modulo::where('programa_estudio_id', $programaId)
            ->where('numero_modulo', '>', $numeroEliminado)
            ->orderBy('numero_modulo', 'asc')
            ->get();

        foreach ($modulosRestantes as $index => $item) {
            Modulo::where('id', $item->id)->update(['numero_modulo' => $numeroEliminado + $index]);
        }

        return redirect()->route('admin.programa-estudio.index')
            ->with('success', 'Módulo eliminado y reordenado correctamente.');
    }
}