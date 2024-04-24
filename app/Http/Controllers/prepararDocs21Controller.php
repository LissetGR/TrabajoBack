<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\preparar_Doc21;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Rules\CamposPermitidos;
class prepararDocs21Controller extends Controller
{
    public function getPreparar(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id' ])],
                'id' => 'required|numeric'
            ]);
            $preparar = preparar_Doc21::findOrFail($validator['id']);
            return response()->json($preparar);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos proporcionados para obtener el registro de la tabla preparar documentos',
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
                '*' => ['sometimes', new CamposPermitidos([ 'doc_provItalia21','solicitud_Trans','delegacion','certificado_residencia','doc_idItaliano'])],
                'doc_provItalia21' => 'required|date|date_format:d/m/Y',
                'solicitud_Trans' => 'required|boolean',
                'delegacion' => 'required|boolean',
                'certificado_residencia' => 'required|boolean',
                'doc_idItaliano' => 'required|boolean',
            ]);

            $preparar = preparar_Doc21::create($validator);

            return response()->json($preparar);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos para crear un registro en la tabla preparar documentos',
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
                '*' => ['sometimes', new CamposPermitidos(['id', 'doc_provItalia21','solicitud_Trans','delegacion','certificado_residencia','doc_idItaliano'])],
                'doc_provItalia21' => 'required|date|date_format:d/m/Y',
                'solicitud_Trans' => 'required|boolean',
                'delegacion' => 'required|boolean',
                'certificado_residencia' => 'required|boolean',
                'doc_idItaliano' => 'required|boolean',
            ]);


            $preparar = preparar_Doc21::findOrFail($request->input('id'));

            $preparar->update($validator);

            return response()->json($preparar);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos para modificar un registro en la tabla preparar documentos',
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
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'id' => 'required|numeric'
            ]);

            $preparar = preparar_Doc21::findOrFail($validator['id']);
            $preparar->delete();


            return response()->json($preparar);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos para eliminar un registro en la tabla preparar documentos',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
