<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Flujo1;
use App\Models\Matrimonio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class flujo1Controller extends Controller
{

    public function getFlujo1()
    {
        try {
            $flujo = Flujo1::all();
            return response()->json($flujo);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function create(Request $request)
    {

        $llegadaController = app('App\Http\Controllers\LlegadaDocs11Controller');

        try {

            DB::beginTransaction();
          try{
            $validator = $request->validate([
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
            ]);

            $llegada = $llegadaController->create($request);
            $llegada = json_encode($llegada->getData());
            $llegada = json_decode(($llegada));

            // if($request->has(''))

            $data = $validator + ['id_llegada_documentos' => $llegada->id];
            $flujo1 = Flujo1::create($data);

            DB::commit();

            $matrimonio = Matrimonio::find($request->input('id_matrimonio'));
            $flujo1->llegadaDocs()->associate($llegada);
            $flujo1->matrimonio()->associate($matrimonio);

            return response()->json($flujo1);

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
            $flujo=Flujo1::find($request->input('id'));
            $flujo->delete();
            $flujo->llegadaDocs()->delete();
            $flujo->formalizarMatrimonio()->delete();
            $flujo->retiroDocs()->delete();
            $flujo->traduccion()->delete();

            return response()->json($flujo);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
