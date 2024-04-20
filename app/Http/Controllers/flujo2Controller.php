<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Flujo2Resource;
use App\Models\Flujo2;
use App\Models\Matrimonio;
use App\Models\preparar_Doc21;
use Illuminate\Http\Request;
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
                'id' => 'required|numeric'
            ]);

            $flujo = Flujo2::where('id_matrimonio', $validator['id'])->get();
            return response()->json(Flujo2Resource::collection($flujo));

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado'+ $e->getMessage(),
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

        $prepararDocs = app('App\Http\Controllers\prepararDocs21Controller');

        try {
            DB::beginTransaction();
            try {
                $validator = $request->validate([
                    'id_matrimonio' => 'required|unique:flujo2s|numeric',
                    'cita_trans' => 'nullable|date|date_format:d/m/Y',
                    'quinto_Email' => 'nullable|date|date_format:d/m/Y',
                    'transc_embajada' => 'nullable|date|date_format:d/m/Y',
                    'sexto_Email' => 'nullable|date|date_format:d/m/Y',
                    'fecha_solicVisa' => 'nullable|date|date_format:d/m/Y',
                    'observaciones' => 'nullable|string',
                ]);

                $preparar = $prepararDocs->create($request);
                $preparar = json_encode($preparar->getData());
                $preparar = json_decode(($preparar));

                if (!property_exists($preparar , 'error')) {
                    $data = $validator + ['id_prepararDocs' => $preparar->id];
                } else {
                    return response()->json([
                        'error' => $preparar->error,
                        'message' => $preparar->message,
                    ], 500);
                }

                $flujo2 = Flujo2::create($data);

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
                'error' => 'Error de validación',
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
                    'id_matrimonio' => 'required|numeric',
                    'cita_trans' => 'nullable|date|date_format:d/m/Y',
                    'quinto_Email' => 'nullable|date|date_format:d/m/Y',
                    'transc_embajada' => 'nullable|date|date_format:d/m/Y',
                    'sexto_Email' => 'nullable|date|date_format:d/m/Y',
                    'fecha_solicVisa' => 'nullable|date|date_format:d/m/Y',
                    'observaciones' => 'nullable|string',
                ]);

                $flujo2 = Flujo2::findOrFail($request->input('id_flujo'));

                if ($request->anyFilled(['doc_provItalia21', 'solicitud_Trans', 'delegacion', 'certificado_residencia', 'fecha_solicVisa'])) {
                    $preparar = $prepararDocs->modificar($request);
                    $preparar = json_encode($preparar->getData());
                    $preparar = json_decode(($preparar));

                    if (!property_exists($preparar , 'error')) {
                        $validator = $validator + ['id_prepararDocs' => $preparar->id];
                        $flujo2->preparacionDocumentos()->associate($preparar);
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
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'id' => 'required|numeric'
            ]);

            $flujo = Flujo2::with('preparacionDocumentos')->findOrFail($validator['id']);
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
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
