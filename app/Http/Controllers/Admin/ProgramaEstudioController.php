<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramaEstudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProgramaEstudioController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.programa-estudio.index')->only(['index']);
        $this->middleware('permission:admin.programa-estudio.store')->only(['store', 'create']);
        $this->middleware('permission:admin.programa-estudio.update')->only(['update', 'edit']);
        $this->middleware('permission:admin.programa-estudio.destroy')->only(['destroy']);
        $this->middleware('permission:admin.programa-estudio.restore')->only(['restore']);
    }

    public function index()
    {
        return view('admin.programa-estudio.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'abreviatura' => 'nullable|string|max:20',
        ]);

        try {
            $validator->validate();

            // Verificar si el Nombre ya existe
            $existingNombre = ProgramaEstudio::where('nombre', $request->nombre)->first();
            
            // Verificar si la Abreviatura ya existe (solo si se envió)
            $existingAbreviatura = $request->abreviatura 
                ? ProgramaEstudio::where('abreviatura', $request->abreviatura)->first() 
                : null;

            // 1. Validar conflictos con el Nombre
            if ($existingNombre) {
                if ($existingNombre->estado === 'inactivo') {
                    // Si también hay conflicto de abreviatura pero con OTRO registro
                    if ($existingAbreviatura && $existingAbreviatura->id !== $existingNombre->id) {
                         return back()->withErrors(['abreviatura' => 'La abreviatura ya está en uso por otro programa.'])->withInput();
                    }
                    
                    // Reactivar
                    $existingNombre->update([
                        'nombre' => $request->nombre,
                        'abreviatura' => $request->abreviatura,
                        'estado' => 'activo',
                    ]);

                    return redirect()->route('admin.programa-estudio.index')
                        ->with('success', 'El programa de estudio existía previamente como inactivo y ha sido reactivado correctamente.');
                
                } else {
                    // Activo
                    return back()->withErrors(['nombre' => 'El nombre del programa ya está registrado.'])->withInput();
                }
            }

            // 2. Validar conflictos con la Abreviatura (si el nombre era nuevo)
            if ($existingAbreviatura) {
                // Si la abreviatura existe (activa o inactiva) y el nombre es nuevo, no podemos reusar el registro de la abreviatura 
                // para sobrescribir su nombre, porque sería cambiar totalmente el programa. 
                // Asumimos que la abreviatura debe ser única globalmente.
                return back()->withErrors(['abreviatura' => 'La abreviatura ya está registrada.'])->withInput();
            }

            // 3. Crear nuevo
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