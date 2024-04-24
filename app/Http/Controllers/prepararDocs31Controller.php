<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\preparar_Docs31;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Rules\CamposPermitidos;

class prepararDocs31Controller extends Controller
{
    public function getPreparar(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id' ])],
                'id' => 'required|numeric'
            ]);

            $preparar = preparar_Docs31::findOrFail($validator['id']);
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
                '*' => ['sometimes', new CamposPermitidos([ 'doc_provItalia31','declaracion_alojamiento', 'reserva_aerea','certificado_residenciaItaliano' ])],
                'doc_provItalia31' => 'required|date|date_format:d/m/Y',
                'declaracion_alojamiento' => 'required|boolean',
                'reserva_aerea' => 'required|boolean',
                'certificado_residenciaItaliano' => 'required|boolean',
            ]);

            $preparar = preparar_Docs31::create($validator);

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
                '*' => ['sometimes', new CamposPermitidos([ 'id','doc_provItalia31','declaracion_alojamiento', 'reserva_aerea','certificado_residenciaItaliano' ])],
                'doc_provItalia31' => 'required|date|date_format:d/m/Y',
                'declaracion_alojamiento' => 'required|boolean',
                'reserva_aerea' => 'required|boolean',
                'certificado_residenciaItaliano' => 'required|boolean',
            ]);

            $preparar = preparar_Docs31::findOrFail($request->input('id'));
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
                '*' => ['sometimes', new CamposPermitidos(['id' ])],
                'id' => 'required|numeric'
            ]);

            $preparar = preparar_Docs31::findOrFail($validator['id']);
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
