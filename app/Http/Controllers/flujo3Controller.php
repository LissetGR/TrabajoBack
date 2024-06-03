<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Flujo3Resource;
use Illuminate\Http\Request;
use App\Models\Flujo3;
use App\Models\Matrimonio;
use App\Models\preparar_Docs31;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Rules\CamposPermitidos;
use Illuminate\Support\Facades\Validator;
class flujo3Controller extends Controller
{
    public function getFlujo3(Request $request)
    {
        try {

            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'numero' => 'required|numeric'
            ]);

            $flujo = Flujo3::where('id_matrimonio', $validator['numero'])->get();
            return response()->json(Flujo3Resource::collection($flujo));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' =>  'Error de validación de los datos para visualizar su registro del flujo ',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function create(Request $request)
    {

        $prepararDocs = app('App\Http\Controllers\prepararDocs31Controller');

        try {
            DB::beginTransaction();
            try {
                $validator = $request->validate([
                    '*' => ['sometimes', new CamposPermitidos(['id_prepararDocs', 'observaciones','id_matrimonio','ultimo_Email', 'retiro_passport','solicitud_visado',  'cita_cubano' ])],
                    'id_matrimonio' => 'required|unique:flujo3s|numeric',
                    'cita_cubano' => 'nullable|date|date_format:d/m/Y',
                    'solicitud_visado' => 'nullable|date|date_format:d/m/Y',
                    'retiro_passport' => 'nullable|date|date_format:d/m/Y',
                    'ultimo_Email' => 'nullable|date|date_format:d/m/Y',
                    'observaciones' => 'nullable|string',
                ]);

                if ($request->input('id_prepararDocs')) {
                    $datos = $request->input('id_prepararDocs');
                    $requestD = new \Illuminate\Http\Request();
                    $requestD->replace($datos);

                    $preparar = $prepararDocs->create($requestD);
                    $preparar = json_encode($preparar->getData());
                    $preparar = json_decode(($preparar));

                    if (!property_exists($preparar , 'error')) {
                        $validator['id_prepararDocs'] = $preparar->id;
                    } else {
                        return response()->json([
                            'error' => $preparar->error,
                            'message' => $preparar->message,
                        ], 500);
                    }
                }

                $flujo3 = Flujo3::create($validator);

                DB::commit();

                $matrimonio = Matrimonio::findOrFail($request->input('id_matrimonio'));
                $flujo3->preparacionDocumentos()->associate($preparar);
                $flujo3->matrimonio()->associate($matrimonio);

                return response()->json($flujo3);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            };
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación de los datos para crear el flujo',
                'message' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro matrimonio',
            ], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function modificar(Request $request)
    {

        $prepararDocs = app('App\Http\Controllers\prepararDocs31Controller');

        try {
            DB::beginTransaction();
            try {
                $validator = $request->validate([
                    '*' => ['sometimes', new CamposPermitidos([ 'id','id_prepararDocs','observaciones','id_matrimonio','ultimo_Email', 'retiro_passport','solicitud_visado',  'cita_cubano' ])],
                    'id' => 'required|numeric',
                    'id_matrimonio' => 'required|numeric',
                    'cita_cubano' => 'nullable|date|date_format:d/m/Y',
                    'solicitud_visado' => 'nullable|date|date_format:d/m/Y',
                    'retiro_passport' => 'nullable|date|date_format:d/m/Y',
                    'ultimo_Email' => 'nullable|date|date_format:d/m/Y',
                    'observaciones' => 'nullable|string',
                ]);

                $flujo3 = Flujo3::findOrFail($request->input('id'));


                if ($request->input('id_prepararDocs')) {
                    $datos = $request->input('id_prepararDocs');
                    $requestD = new \Illuminate\Http\Request();
                    $requestD->replace($datos);

                    if ($request->has('id_prepararDocs.id')) {
                        $preparar = $prepararDocs->modificar($requestD);
                    } else {
                        $preparar = $prepararDocs->create($requestD);
                    }

                    $preparar = json_encode($preparar->getData());
                    $preparar = json_decode(($preparar));

                    if (!property_exists($preparar , 'error')) {
                        $validator['id_prepararDocs'] = $preparar->id;
                    } else {
                        return response()->json([
                            'error' => $preparar->error,
                            'message' => $preparar->message,
                        ], 500);
                    }
                }

                $flujo3->update($validator);

                DB::commit();

                $matrimonio = Matrimonio::findOrFail($request->input('id_matrimonio'));
                $flujo3->matrimonio()->associate($matrimonio);

                return response()->json(new Flujo3Resource($flujo3));
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            };
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación de los datos para modificar el flujo',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make(
                ['numero' => $request->header('numero')],
                [
                    '*' => ['sometimes', new CamposPermitidos(['numero'])],
                    'numero' => 'required|numeric',
                ]
            );


            $flujo = Flujo3::with('preparacionDocumentos')->where('id_matrimonio', $validator->getData()['numero'])->first();
            $flujo->delete();
            $flujo->preparacionDocumentos()->delete();

            return response()->json(new Flujo3Resource($flujo));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación de los datos para eliminar el flujo',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
