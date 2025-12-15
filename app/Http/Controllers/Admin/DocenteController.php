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
                Log::info('Respuesta API DNI:', $data); // Importante: Revisa esto en laravel.log si falla

                // 1. Detección de errores lógicos dentro de un 200 OK
                // A veces la API dice OK, pero manda {"message": "DNI no encontrado"}
                if (isset($data['message']) && !isset($data['response']) && !isset($data['nombres']) && !isset($data['first_name'])) {
                    return response()->json(['error' => 'No se encontró a la persona.'], 404);
                }

                // 2. Búsqueda flexible de datos (Soporta V1, V2 y Decolecta)
                // Prioridad: 1. Dentro de 'response', 2. En la raíz, 3. Dentro de 'data'
                $datos = $data['response'] ?? $data['data'] ?? $data;

                // 3. Mapeo de campos (inglés o español)
                $nombres = $datos['first_name'] ?? $datos['nombres'] ?? '';
                $apellidoPaterno = $datos['first_last_name'] ?? $datos['apellidoPaterno'] ?? $datos['apellido_paterno'] ?? '';
                $apellidoMaterno = $datos['second_last_name'] ?? $datos['apellidoMaterno'] ?? $datos['apellido_materno'] ?? '';
                $numero = $datos['document_number'] ?? $datos['numeroDocumento'] ?? $datos['dni'] ?? $dni;

                // Validación final: Si no pudimos rescatar ni el nombre, algo salió mal
                if (empty($nombres)) {
                    Log::error('Estructura API no reconocida', ['data' => $data]);
                    return response()->json([
                        'error' => 'No se encontró a la persona.'
                    ], 404);
                }

                $apellidos = trim("{$apellidoPaterno} {$apellidoMaterno}");

                // Fallback por si la API da el nombre completo en un solo campo
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
                // Capturar error HTTP real (401, 403, 500)
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

    private function normalizeApellidos(array $data): string
    {
        // Manejar diferentes estructuras de la respuesta
        if (isset($data['apellidos']) && !empty($data['apellidos'])) {
            return $data['apellidos'];
        }

        // Ajustar para camelCase como devuelve la API
        $apellidoPaterno = $data['apellidoPaterno'] ?? '';
        $apellidoMaterno = $data['apellidoMaterno'] ?? '';
        $apellidos = trim("{$apellidoPaterno} {$apellidoMaterno}");

        if (empty($apellidos)) {
            // Intentar con otros posibles campos
            $apellidos = $data['nombreCompleto'] ?? $data['apellido'] ?? $data['apellidos_completos'] ?? '';
            // Si nombreCompleto está presente, extraer solo los apellidos
            if (!empty($apellidos) && isset($data['nombres'])) {
                $apellidos = str_replace($data['nombres'], '', $apellidos);
                $apellidos = trim($apellidos);
            }
        }

        return $apellidos;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:docentes,email',
            'tipo_documento' => 'required|string|in:DNI,CE|max:20',
            'numero_documento' => 'required|string|digits:8|unique:docentes,numero_documento',
            'nivel_academico' => 'nullable|in:Bachiller,Técnico,Licenciado,Ingeniero,Magister,Doctor',
        ]);

        try {
            $validator->validate();

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
            'nivel_academico' => 'nullable|in:Bachiller,Técnico,Licenciado,Ingeniero,Magister,Doctor',
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