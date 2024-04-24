<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\observaciones;
use App\Models\Matrimonio;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Rules\CamposPermitidos;

class observacionesController extends Controller
{
    public function getObservaciones(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'id' => 'required|numeric'
            ]);
            $observaciones = observaciones::where('id_matrimonio', $validator['id'])->get();
            return response()->json($observaciones);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos para obtener el registro de la tabla observaciones',
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
                '*' => ['sometimes', new CamposPermitidos(['id_matrimonio','descripcion' ])],
                'id_matrimonio' => 'required|numeric',
                'descripcion' => 'required|string'
            ]);

            $matrimonio = Matrimonio::findOrFail($request->input('id_matrimonio'));
            $observaciones = observaciones::create($validator);

            $observaciones->matrimonio()->associate($matrimonio);

            return response()->json($observaciones);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos para crear el registro de la tabla observaciones',
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
                '*' => ['sometimes', new CamposPermitidos(['id','id_matrimonio','descripcion' ])],
                'id_matrimonio' => 'required|numeric',
                'descripcion' => 'required|string'
            ]);

            $matrimonio = Matrimonio::findOrFail($request->input('id_matrimonio'));

            $observaciones = observaciones::findOrFail($request->input('id'));
            $observaciones->update($validator);

            $observaciones->matrimonio()->associate($matrimonio);

            return response()->json($observaciones);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos para modificar el registro de la tabla observaciones',
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

            $observaciones = observaciones::findOrFail($validator['id']);
            $observaciones->delete();

            return response()->json($observaciones);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación de los datos para eliminar el registro de la table observaciones',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
