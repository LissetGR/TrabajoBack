<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\llegada_Doc11;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class llegadaDocs11Controller extends Controller
{
    public function getllegadaDoc(Request $request)
    {
        try {

            $validator = $request->validate([
                'id' => 'required|numeric'
            ]);


            $llegada = llegada_Doc11::findOrFail($validator['id']);
            return response()->json($llegada);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {

            $validator = $request->validate([
                'fecha' => 'required|date|date_format:d/m/Y',
                'doc1' => 'required', 'string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Cert. di nascita', 'Procura'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                },
                'doc2' => 'required', 'string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Stato libero', 'Sentenza di divorzio', 'Atto di morte'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                }
            ]);

            $llegada = llegada_Doc11::create([
                'fecha' => $validator['fecha'],
                'doc1' => Str::ucfirst($validator['doc1']),
                'doc2' => Str::ucfirst($validator['doc2']),
            ]);

            return response()->json($llegada);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function modificar(Request $request)
    {
        try {

            $validator = $request->validate([
                'fecha' => 'required|date|date_format:d/m/Y',
                'doc1' => 'required', 'string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Cert. di nascita', 'Procura'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                },
                'doc2' => 'required', 'string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Stato libero', 'Sentenza di divorzio', 'Atto di morte'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                }
            ]);

            $llegada = llegada_Doc11::findOrFail($request->input(('id')));

            $llegada->update($validator);

            return response()->json($llegada);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {

            $validator = $request->validate([
                'id' => 'required|numeric'
            ]);

            $llegada = llegada_Doc11::findOrFail($validator['id']);
            $llegada->delete();


            return response()->json($llegada);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
