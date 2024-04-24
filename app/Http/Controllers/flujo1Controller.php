<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Flujo1Resource;
use App\Models\Flujo1;
use App\Models\formalizar_Matrim12;
use App\Models\llegada_Doc11;
use App\Models\Matrimonio;
use App\Models\retirar_Doc13;
use App\Models\traduccion14;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Rules\CamposPermitidos;

class flujo1Controller extends Controller
{

    public function getFlujo1(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'numero' => 'required|numeric'
            ]);

            $flujo = Flujo1::where('id_matrimonio', $validator['numero'])->get();
            return response()->json(Flujo1Resource::collection($flujo));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación de los datos para visualizar su registro del flujo ',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function create(Request $request)
    {

        $llegadaController = app('App\Http\Controllers\LlegadaDocs11Controller');
        $formalizarController = app('App\Http\Controllers\FormalizarMatrimonio12Controller');
        $retirarController = app('App\Http\Controllers\RetirarDocs13Controller');
        $traduccionController = app('App\Http\Controllers\Traduccion14Controller');

        try {

            DB::beginTransaction();
            try {
                $validator = $request->validate([
                    '*' => ['sometimes', new CamposPermitidos(['id_llegada_documentos', 'id_traduccion', 'id', 'id_retiroDocsMinrex','id_formalizarMatrimonio','observaciones','procura_minrex', 'cm_minrex', 'tercer_Email', 'segundo_Email', 'id_matrimonio', 'primer_Email', 'email_Cubano', 'cuarto_Email', 'coordinar_Matrim'])],
                    'id_matrimonio' => 'required|unique:flujo1s|numeric',
                    'primer_Email' => 'nullable|date|date_format:d/m/Y',
                    'email_Cubano' => 'nullable|date|date_format:d/m/Y',
                    'coordinar_Matrim' => 'nullable|date|date_format:d/m/Y',
                    'segundo_Email' => 'nullable|date|date_format:d/m/Y',
                    'procura_minrex' => 'nullable|date|date_format:d/m/Y',
                    'retirada_CM' => 'nullable|date|date_format:d/m/Y',
                    'tercer_Email' => 'nullable|date|date_format:d/m/Y',
                    'cm_minrex' => 'nullable|date|date_format:d/m/Y',
                    'cuarto_Email' => 'nullable|date|date_format:d/m/Y',
                    'observaciones' => 'nullable|string',
                ]);


                if ($request->input('id_llegada_documentos')) {
                    $datos = $request->input('id_llegada_documentos');
                    $requestD = new \Illuminate\Http\Request();
                    $requestD->replace($datos);

                    $llegada = $llegadaController->create($requestD);
                    $llegada = json_encode($llegada->getData());
                    $llegada = json_decode(($llegada));

                    if (!property_exists($llegada, 'error')) {
                        $validator['id_llegada_documentos'] = $llegada->id;
                    } else {
                        return response()->json([
                            'error' => $llegada->error,
                            'message' => $llegada->message,
                        ], 500);
                    }
                }


                if ($request->input('id_formalizarMatrimonio')) {
                    $datos = $request->input('id_formalizarMatrimonio');
                    $requestD = new \Illuminate\Http\Request();
                    $requestD->replace($datos);

                    $formalizar = $formalizarController->create($requestD);

                    $formalizar = json_encode($formalizar->getData());
                    $formalizar = json_decode($formalizar);

                    if (!property_exists($formalizar, 'error')) {
                        $validator['id_formalizarMatrimonio'] =  $formalizar->id;
                    } else {
                        return response()->json([
                            'error' => $formalizar->error,
                            'message' => $formalizar->message,
                        ], 500);
                    }
                }
                if ($request->input('id_retiroDocsMinrex')) {
                    $datos = $request->input('id_retiroDocsMinrex');
                    $requestD = new \Illuminate\Http\Request();
                    $requestD->replace($datos);


                    $retirar = $retirarController->create($requestD);


                    $retirar = json_encode($retirar->getData());
                    $retirar = json_decode(($retirar));

                    if (!property_exists($retirar, 'error')) {
                        $validator['id_retiroDocsMinrex'] = $retirar->id;
                    } else {
                        return response()->json([
                            'error' => $retirar->error,
                            'message' => $retirar->message,
                        ], 500);
                    }
                }
                if ($request->input('id_traduccion')) {
                    $datos = $request->input('id_traduccion');
                    $requestD = new \Illuminate\Http\Request();
                    $requestD->replace($datos);


                    $traduccion = $traduccionController->create($requestD);

                    $traduccion = json_encode($traduccion->getData());
                    $traduccion = json_decode(($traduccion));

                    if (!property_exists($traduccion, 'error')) {
                        $validator['id_traduccion'] = $traduccion->id;
                    } else {
                        return response()->json([
                            'error' => $traduccion->error,
                            'message' => $traduccion->message,
                        ], 500);
                    }
                }

                $flujo1 = Flujo1::create($validator);

                DB::commit();

                $matrimonio = Matrimonio::findOrFail($request->input('id_matrimonio'));
                $flujo1->llegadaDocs()->associate($llegada);
                if (isset($formalizar) && $formalizar) {
                    $flujo1->formalizarMatrimonio()->associate($formalizar);
                }
                if (isset($retirar) && $retirar) {
                    $flujo1->retiroDocs()->associate($retirar);
                }
                if (isset($traduccion) && $traduccion) {
                    $flujo1->traduccion()->associate($traduccion);
                }
                $flujo1->matrimonio()->associate($matrimonio);

                return response()->json($flujo1);
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
                'error' => 'Error de validación de los datos para crear el flujo',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function modificar(Request $request)
    {

        $llegadaController = app('App\Http\Controllers\LlegadaDocs11Controller');
        $formalizarController = app('App\Http\Controllers\FormalizarMatrimonio12Controller');
        $retirarController = app('App\Http\Controllers\RetirarDocs13Controller');
        $traduccionController = app('App\Http\Controllers\Traduccion14Controller');

        try {

            DB::beginTransaction();
            try {
                $validator = $request->validate([
                    '*' => ['sometimes', new CamposPermitidos(['id_formalizarMatrimonio', 'created_at', 'updated_at', 'id_llegada_documentos', 'id_traduccion', 'id', 'id_retiroDocsMinrex', 'observaciones', 'cm_minrex', 'tercer_Email', 'retirada_CM', 'procura_minrex', 'segundo_Email', 'id_matrimonio', 'primer_Email', 'email_Cubano', 'cuarto_Email',  'coordinar_Matrim'])],
                    'id_matrimonio' => 'required|numeric',
                    'id' => 'required|numeric',
                    'primer_Email' => 'nullable|date|date_format:d/m/Y',
                    'email_Cubano' => 'nullable|date|date_format:d/m/Y',
                    'coordinar_Matrim' => 'nullable|date|date_format:d/m/Y',
                    'segundo_Email' => 'nullable|date|date_format:d/m/Y',
                    'procura_minrex' => 'nullable|date|date_format:d/m/Y',
                    'retirada_CM' => 'nullable|date|date_format:d/m/Y',
                    'tercer_Email' => 'nullable|date|date_format:d/m/Y',
                    'cm_minrex' => 'nullable|date|date_format:d/m/Y',
                    'cuarto_Email' => 'nullable|date|date_format:d/m/Y',
                    'observaciones' => 'nullable|string',
                ]);


                $flujo1 = Flujo1::findOrFail($request->input('id'));

                if ($request->input('id_llegada_documentos')) {
                    $datos = $request->input('id_llegada_documentos');
                    $requestD = new \Illuminate\Http\Request();
                    $requestD->replace($datos);

                    $llegada = $llegadaController->modificar($requestD);
                    $llegada = json_encode($llegada->getData());
                    $llegada = json_decode(($llegada));

                    if (!property_exists($llegada, 'error')) {
                        $validator['id_llegada_documentos'] = $llegada->id;
                    } else {
                        return response()->json([
                            'error' => $llegada->error,
                            'message' => $llegada->message,
                        ], 500);
                    }
                }

                if ($request->input('id_formalizarMatrimonio')) {
                    $datos = $request->input('id_formalizarMatrimonio');
                    $requestD = new \Illuminate\Http\Request();
                    $requestD->replace($datos);

                    if ($request->has('id_formalizarMatrimonio.id')) {
                        $formalizar = $formalizarController->modificar($requestD);
                    } else {
                        $formalizar = $formalizarController->create($requestD);
                    }
                    $formalizar = json_encode($formalizar->getData());
                    $formalizar = json_decode($formalizar);

                    if (!property_exists($formalizar, 'error')) {
                        $validator['id_formalizarMatrimonio'] =  $formalizar->id;
                    } else {
                        return response()->json([
                            'error' => $formalizar->error,
                            'message' => $formalizar->message,
                        ], 500);
                    }
                }
                if ($request->input('id_retiroDocsMinrex')) {
                    $datos = $request->input('id_retiroDocsMinrex');
                    $requestD = new \Illuminate\Http\Request();
                    $requestD->replace($datos);

                    if ($request->has('id_retiroDocsMinrex.id')) {
                        $retirar = $retirarController->modificar($requestD);
                    } else {
                        $retirar = $retirarController->create($requestD);
                    }

                    $retirar = json_encode($retirar->getData());
                    $retirar = json_decode(($retirar));

                    if (!property_exists($retirar, 'error')) {
                        $validator['id_retiroDocsMinrex'] = $retirar->id;
                    } else {
                        return response()->json([
                            'error' => $retirar->error,
                            'message' => $retirar->message,
                        ], 500);
                    }
                }
                if ($request->input('id_traduccion')) {
                    $datos = $request->input('id_traduccion');
                    $requestD = new \Illuminate\Http\Request();
                    $requestD->replace($datos);

                    if ($request->has('id_traduccion.id')) {
                        $traduccion = $traduccionController->modificar($requestD);
                    } else {
                        $traduccion = $traduccionController->create($requestD);
                    }
                    $traduccion = json_encode($traduccion->getData());
                    $traduccion = json_decode(($traduccion));

                    if (!property_exists($traduccion, 'error')) {
                        $validator['id_traduccion'] = $traduccion->id;
                    } else {
                        return response()->json([
                            'error' => $traduccion->error,
                            'message' => $traduccion->message,
                        ], 500);
                    }
                }

                DB::commit();
                $flujo1->update($validator);

                $matrimonio = Matrimonio::findOrFail($request->input('id_matrimonio'));
                $flujo1->matrimonio()->associate($matrimonio);

                return response()->json(new Flujo1Resource($flujo1));
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
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'numero' => 'required|numeric'
            ]);

            $flujo = Flujo1::with(['llegadaDocs', 'formalizarMatrimonio', 'retiroDocs', 'traduccion'])->where('id_matrimonio', $validator['numero'])->first();

            $flujo->delete();
            $flujo->llegadaDocs()->delete();
            $flujo->formalizarMatrimonio()->delete();
            $flujo->retiroDocs()->delete();
            $flujo->traduccion()->delete();
            return response()->json(new Flujo1Resource($flujo));
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
