<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Flujo2Resource;
use App\Models\Flujo2;
use App\Models\Matrimonio;
use App\Models\preparar_Doc21;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Rules\CamposPermitidos;

class flujo2Controller extends Controller
{
    public function getFlujo2(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'numero' => 'required|numeric'
            ]);

            $flujo = Flujo2::where('id_matrimonio', $validator['numero'])->get();
            return response()->json(Flujo2Resource::collection($flujo));

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado'+ $e->getMessage(),
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' =>'Error de validación de los datos para visualizar su registro del flujo ',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function create(Request $request)
    {

        $prepararDocs = app('App\Http\Controllers\prepararDocs21Controller');

        try {
            DB::beginTransaction();
            try {
                $validator = $request->validate([
                    '*' => ['sometimes', new CamposPermitidos(['id_prepararDocs','id_matrimonio','cita_trans','quinto_Email','transc_embajada','sexto_Email','fecha_solicVisa','observaciones'])],
                    'id_matrimonio' => 'required|unique:flujo2s|numeric',
                    'cita_trans' => 'nullable|date|date_format:d/m/Y',
                    'quinto_Email' => 'nullable|date|date_format:d/m/Y',
                    'transc_embajada' => 'nullable|date|date_format:d/m/Y',
                    'sexto_Email' => 'nullable|date|date_format:d/m/Y',
                    'fecha_solicVisa' => 'nullable|date|date_format:d/m/Y',
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

                $flujo2 = Flujo2::create($validator);

                DB::commit();

                $matrimonio = Matrimonio::findOrFail($request->input('id_matrimonio'));
                $flujo2->preparacionDocumentos()->associate($preparar);
                $flujo2->matrimonio()->associate($matrimonio);

                return response()->json($flujo2);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            };
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación de los datos para crear el flujo',
                'message' => $e->errors(),
            ], 422);
        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro matrimonio',
            ], 404);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function modificar(Request $request)
    {

        $prepararDocs = app('App\Http\Controllers\prepararDocs21Controller');

        try {
            DB::beginTransaction();
            try {
                $validator = $request->validate([
                    '*' => ['sometimes', new CamposPermitidos(['id_prepararDocs','id','id_matrimonio','cita_trans','quinto_Email','transc_embajada','sexto_Email','fecha_solicVisa','observaciones'])],
                    'id' => 'required|numeric',
                    'id_matrimonio' => 'required|numeric',
                    'cita_trans' => 'nullable|date|date_format:d/m/Y',
                    'quinto_Email' => 'nullable|date|date_format:d/m/Y',
                    'transc_embajada' => 'nullable|date|date_format:d/m/Y',
                    'sexto_Email' => 'nullable|date|date_format:d/m/Y',
                    'fecha_solicVisa' => 'nullable|date|date_format:d/m/Y',
                    'observaciones' => 'nullable|string',
                ]);

                $flujo2 = Flujo2::findOrFail($request->input('id'));

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

                $flujo2->update($validator);

                DB::commit();
                $matrimonio = Matrimonio::findOrFail($request->input('id_matrimonio'));
                $flujo2->matrimonio()->associate($matrimonio);

                return response()->json(new Flujo2Resource($flujo2));
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

            $flujo = Flujo2::with('preparacionDocumentos')->where('id_matrimonio',$validator->getData()['numero'])->first();
            $flujo->delete();
            $flujo->preparacionDocumentos()->delete();

            return response()->json(new Flujo2Resource($flujo));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' =>  'Error de validación de los datos para eliminar el flujo',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
