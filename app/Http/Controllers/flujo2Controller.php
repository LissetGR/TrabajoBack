<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Flujo2Resource;
use App\Models\Flujo2;
use App\Models\Matrimonio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class flujo2Controller extends Controller
{
    public function getFlujo2(Request $request){
        try{
               $flujo=Flujo2::where('id_matrimonio',$request->input('id'))->get();
               return response()->json(Flujo2Resource::collection($flujo));
           }catch(\Exception $e){
               return response()->json($e->getMessage());
           }

    }

    public function create(Request $request)
    {

        $prepararDocs = app('App\Http\Controllers\prepararDocs21Controller');

        try {
            DB::beginTransaction();
            try{
            $validator = $request->validate([
                'id_matrimonio' => 'required|unique:flujo2s|numeric',
                'cita_trans' => 'nullable|date|date_format:d/m/Y',
                'quinto_Email' => 'nullable|date|date_format:d/m/Y',
                'transc_embajada' => 'nullable|date|date_format:d/m/Y',
                'sexto_Email' => 'nullable|date|date_format:d/m/Y',
                'fecha_solicVisa' => 'nullable|date|date_format:d/m/Y',
                'observaciones'=>'nullable|string',
            ]);

            $preparar = $prepararDocs->create($request);
            $preparar = json_encode($preparar->getData());
            $preparar = json_decode(($preparar));


            $data = $validator + ['id_prepararDocs' => $preparar->id];
            $flujo2 = Flujo2::create($data);

            DB::commit();

            $matrimonio = Matrimonio::find($request->input('id_matrimonio'));
            $flujo2->preparacionDocumentos()->associate($preparar);
            $flujo2->matrimonio()->associate($matrimonio);

            return response()->json($flujo2);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        };
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function modificar(Request $request)
    {

        $prepararDocs = app('App\Http\Controllers\prepararDocs21Controller');

        try {
            DB::beginTransaction();
            try{
            $validator = $request->validate([
                'id_matrimonio' => 'required|numeric',
                'cita_trans' => 'nullable|date|date_format:d/m/Y',
                'quinto_Email' => 'nullable|date|date_format:d/m/Y',
                'transc_embajada' => 'nullable|date|date_format:d/m/Y',
                'sexto_Email' => 'nullable|date|date_format:d/m/Y',
                'fecha_solicVisa' => 'nullable|date|date_format:d/m/Y',
                'observaciones'=>'nullable|string',
            ]);

            $flujo2 = Flujo2::findOrFail($request->input('id_flujo'));

            if($request->anyFilled(['doc_provItalia21','solicitud_Trans','delegacion','certificado_residencia', 'fecha_solicVisa'])){
                $preparar = $prepararDocs->modificar($request);
                $preparar = json_encode($preparar->getData());
                $preparar = json_decode(($preparar));
                $data = $validator + ['id_prepararDocs' => $preparar->id];
                $flujo2->update($data);
                $flujo2->preparacionDocumentos()->associate($preparar);
            }else{
                $flujo2->update($validator);
            }

            DB::commit();
            $matrimonio = Matrimonio::find($request->input('id_matrimonio'));
            $flujo2->matrimonio()->associate($matrimonio);

            return response()->json($flujo2);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        };
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy (Request $request){
        try{
            $flujo=Flujo2::find($request->input('id'));
            $flujo->delete();
            $flujo->preparacionDocumentos()->delete();

            return response()->json(new Flujo2Resource($flujo));
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
