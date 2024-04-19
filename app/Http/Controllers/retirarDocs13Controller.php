<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\retirar_Doc13;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class retirarDocs13Controller extends Controller
{
    public function getRetirar(Request $request)
    {
        try {
            $validator = $request->validate([
                'id' => 'required|numeric'
            ]);

            $retirar = retirar_Doc13::findOrFail($validator['id']);
            return response()->json($retirar);
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
                'fecha_Procura' => 'required|date|date_format:d/m/Y',
                'fecha_Matrimonio' => 'required|date|date_format:d/m/Y'
            ]);

            $retiro = retirar_Doc13::create([
                'fecha_Procura' => $validator['fecha_Procura'],
                'fecha_Matrimonio' => $validator['fecha_Matrimonio']
            ]);

            return response()->json($retiro);
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
                'fecha_Procura' => 'required|date|date_format:d/m/Y',
                'fecha_Matrimonio' => 'required|date|date_format:d/m/Y'
            ]);

            $retiro = retirar_Doc13::findOrFail($request->input('id'));

            $retiro->update($validator);

            return response()->json($retiro);
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

            $retirar = retirar_Doc13::findOrFail($validator['id']);
            $retirar->delete();


            return response()->json($retirar);
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
