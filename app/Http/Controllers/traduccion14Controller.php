<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\traduccion14;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Rules\CamposPermitidos;

class traduccion14Controller extends Controller
{
    public function getTraduccion(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos([ 'id'])],
                'id' => 'required|numeric'
            ]);
            $traduccion = traduccion14::findOrFail($validator['id']);
            return response()->json($traduccion);
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
                '*' => ['sometimes', new CamposPermitidos(['fecha_Procura','fecha_Matrimonio' ])],
                'fecha_Procura' => 'required|date|date_format:d/m/Y',
                'fecha_Matrimonio' => 'required|date|date_format:d/m/Y'
            ]);

            $traduccion = traduccion14::create([
                'fecha_Procura' => $validator['fecha_Procura'],
                'fecha_Matrimonio' => $validator['fecha_Matrimonio']
            ]);

            return response()->json($traduccion);
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
                '*' => ['sometimes', new CamposPermitidos([ 'id','fecha_Procura','fecha_Matrimonio' ])],
                'fecha_Procura' => 'required|date|date_format:d/m/Y',
                'fecha_Matrimonio' => 'required|date|date_format:d/m/Y'
            ]);

            $traduccion = traduccion14::findOrFail($request->input('id'));
            $traduccion->update($validator);

            return response()->json($traduccion);
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
                '*' => ['sometimes', new CamposPermitidos([ 'id'])],
                'id' => 'required|numeric'
            ]);

            $traduccion = traduccion14::findOrFail($validator['id']);
            $traduccion->delete();

            return response()->json($traduccion);
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
