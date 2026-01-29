<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DocenteController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.docente.index')->only(['index']);
        $this->middleware('permission:admin.docente.store')->only(['store', 'create']);
        $this->middleware('permission:admin.docente.update')->only(['update', 'edit']);
        $this->middleware('permission:admin.docente.destroy')->only(['destroy']);
        $this->middleware('permission:admin.docente.restore')->only(['restore']);
        $this->middleware('permission:admin.docente.consultar-dni')->only(['consultarDni']);
    }

    public function index()
    {
        return view('admin.docente.index');
    }

    public function consultarDni(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'required|digits:8',
            'tipo_documento' => 'required|in:DNI,CE',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $dni = $request->input('dni');
        $url = "https://api.decolecta.com/v1/reniec/dni?numero={$dni}";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('APIS_NET_PE_TOKEN'),
                'Accept' => 'application/json',
            ])->withOptions([
                        'verify' => false,
                    ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Respuesta API DNI:', $data);

                if (isset($data['message']) && !isset($data['response']) && !isset($data['nombres']) && !isset($data['first_name'])) {
                    return response()->json(['error' => 'No se encontró a la persona.'], 404);
                }

                $datos = $data['response'] ?? $data['data'] ?? $data;

                $nombres = $datos['first_name'] ?? $datos['nombres'] ?? '';
                $apellidoPaterno = $datos['first_last_name'] ?? $datos['apellidoPaterno'] ?? $datos['apellido_paterno'] ?? '';
                $apellidoMaterno = $datos['second_last_name'] ?? $datos['apellidoMaterno'] ?? $datos['apellido_materno'] ?? '';
                $numero = $datos['document_number'] ?? $datos['numeroDocumento'] ?? $datos['dni'] ?? $dni;

                if (empty($nombres)) {
                    Log::error('Estructura API no reconocida', ['data' => $data]);
                    return response()->json([
                        'error' => 'No se encontró a la persona.'
                    ], 404);
                }

                $apellidos = trim("{$apellidoPaterno} {$apellidoMaterno}");

                if (empty($apellidos) && isset($datos['apellidos'])) {
                    $apellidos = $datos['apellidos'];
                }

                return response()->json([
                    'numero' => $numero,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'tipo_documento_api' => 'DNI',
                    'digito_verificador' => $datos['codVerifica'] ?? ''
                ]);

            } else {
                if ($response->status() === 404) {
                    return response()->json(['error' => 'No se encontró a la persona.'], 404);
                }
                $msg = $response->json()['message'] ?? 'Error desconocido';
                return response()->json(['error' => "Error API ({$response->status()}): {$msg}"], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Excepción API', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error de conexión: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tipo_documento' => 'required|string|in:DNI,CE|max:20',
            'numero_documento' => 'required|string|digits:8',
            'nivel_academico' => 'required|in:Bachiller,Técnico,Licenciado,Ingeniero,Magister,Doctor',
        ]);

        try {
            $validator->validate();

            // Verificar si el DNI ya existe
            $existingDni = Docente::where('numero_documento', $request->numero_documento)->first();
            
            // Verificar si el Email ya existe
            $existingEmail = Docente::where('email', $request->email)->first();

            // Caso 1: DNI ya registrado
            if ($existingDni) {
                if ($existingDni->estado === 'inactivo') {
                    // Si el email también existe y pertenece a OTRA persona, es un error
                    if ($existingEmail && $existingEmail->id !== $existingDni->id) {
                        return back()->withErrors(['email' => 'El correo electrónico ya está registrado por otro usuario.'])->withInput();
                    }

                    // Reactivar y actualizar
                    $existingDni->update([
                        'nombres' => $request->nombres,
                        'apellidos' => $request->apellidos,
                        'email' => $request->email,
                        'tipo_documento' => $request->tipo_documento,
                        // 'numero_documento' no cambia porque es el que usamos para buscar
                        'nivel_academico' => $request->nivel_academico,
                        'estado' => 'activo',
                    ]);

                    return redirect()->route('admin.docente.index')
                        ->with('success', 'El docente existía previamente como inactivo y ha sido reactivado correctamente.');
                } else {
                    // DNI existe y está activo
                    return back()->withErrors(['numero_documento' => 'El número de documento ya está registrado.'])->withInput();
                }
            }

            // Caso 2: DNI nuevo, pero Email ya registrado
            if ($existingEmail) {
                // Ya sea activo o inactivo, si el DNI es nuevo, no podemos duplicar emails
                return back()->withErrors(['email' => 'El correo electrónico ya está registrado.'])->withInput();
            }

            // Caso 3: Todo limpio, crear nuevo
            Docente::create([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'nivel_academico' => $request->nivel_academico,
                'estado' => 'activo',
            ]);

            return redirect()->route('admin.docente.index')
                ->with('success', 'El docente fue registrado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:docentes,email,' . $id,
            'tipo_documento' => 'required|string|in:DNI,CE|max:20',
            'numero_documento' => 'required|string|max:8|unique:docentes,numero_documento,' . $id,
            'nivel_academico' => 'required|in:Bachiller,Técnico,Licenciado,Ingeniero,Magister,Doctor',
        ]);

        try {
            $validator->validate();

            $docente = Docente::findOrFail($id);

            $docente->update([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'nivel_academico' => $request->nivel_academico,
            ]);

            return redirect()->route('admin.docente.index')
                ->with('success', 'El docente fue actualizado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $docente = Docente::findOrFail($id);
        $docente->update(['estado' => 'inactivo']);

        return redirect()->route('admin.docente.index')
            ->with('success', 'El docente fue desactivado correctamente.');
    }

    public function restore(string $id)
    {
        $docente = Docente::findOrFail($id);
        $docente->update(['estado' => 'activo']);

        return redirect()->route('admin.docente.index')
            ->with('success', 'El docente fue restaurado correctamente.');
    }
}