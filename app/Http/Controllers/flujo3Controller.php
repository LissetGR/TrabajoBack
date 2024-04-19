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

class flujo3Controller extends Controller
{
    public function getFlujo3(Request $request)
    {
        try {

            $validator = $request->validate([
                'id' => 'required|numeric'
            ]);

            $flujo = Flujo3::where('id_matrimonio', $validator['id'])->get();
            return response()->json(Flujo3Resource::collection($flujo));
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

        $prepararDocs = app('App\Http\Controllers\prepararDocs31Controller');

        try {
            DB::beginTransaction();
            try {
                $validator = $request->validate([
                    'id_matrimonio' => 'required|unique:flujo3s|numeric',
                    'cita_cubano' => 'nullable|date|date_format:d/m/Y',
                    'solicitud_visado' => 'nullable|date|date_format:d/m/Y',
                    'retiro_passport' => 'nullable|date|date_format:d/m/Y',
                    'ultimo_Email' => 'nullable|date|date_format:d/m/Y',
                    'observaciones' => 'nullable|string',
                ]);

                $preparar = $prepararDocs->create($request);
                $preparar = json_encode($preparar->getData());
                $preparar = json_decode(($preparar));

                if (!property_exists($preparar, 'error')) {
                    $data = $validator + ['id_prepararDocs' => $preparar->id];
                } else {
                    return response()->json([
                        'error' => $preparar->error,
                        'message' => $preparar->message,
                    ], 500);
                }

                $flujo3 = Flujo3::create($data);

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
                'error' => 'Error de validación',
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
                    'id_matrimonio' => 'required|numeric',
                    'cita_cubano' => 'nullable|date|date_format:d/m/Y',
                    'solicitud_visado' => 'nullable|date|date_format:d/m/Y',
                    'retiro_passport' => 'nullable|date|date_format:d/m/Y',
                    'ultimo_Email' => 'nullable|date|date_format:d/m/Y',
                    'observaciones' => 'nullable|string',
                ]);

                $flujo3 = Flujo3::findOrFail($request->input('id_flujo'));


                if ($request->anyFilled(['doc_provItalia31', 'declaracion_alojamiento', 'reserva_aerea', 'certificado_residenciaItaliano'])) {
                    $preparar = $prepararDocs->create($request);
                    $preparar = json_encode($preparar->getData());
                    $preparar = json_decode(($preparar));

                    if (!property_exists($preparar, 'error')) {
                        $validator = $validator + ['id_prepararDocs' => $preparar->id];
                        $flujo3->preparacionDocumentos()->associate($preparar);
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

            $flujo = Flujo3::with('preparacionDocumentos')->findOrFail($validator['id']);
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
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
