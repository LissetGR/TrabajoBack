<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Flujo2;
use App\Models\Matrimonio;
use Illuminate\Http\Request;

class flujo2Controller extends Controller
{
    public function getFlujo2(){
        try{
               $flujo=Flujo2::all();
               return response()->json($flujo);
           }catch(\Exception $e){
               return response()->json($e->getMessage());
           }

    }

    public function create(Request $request)
    {

        $prepararDocs = app('App\Http\Controllers\prepararDocs21Controller');

        try {
            $validator = $request->validate([
                'id_matrimonio' => 'required|unique:flujo2s|numeric',
                'cita_trans' => 'nullable|date|date_format:d/m/Y',
                'quinto_Email' => 'nullable|date|date_format:d/m/Y',
                'transc_embajada' => 'nullable|date|date_format:d/m/Y',
                'sexto_Email' => 'nullable|date|date_format:d/m/Y',
                'fecha_solicVisa' => 'nullable|date|date_format:d/m/Y',
            ]);

            $preparar = $prepararDocs->create($request);
            $preparar = json_encode($preparar->getData());
            $preparar = json_decode(($preparar));

            // if($request->has(''))

            $data = $validator + ['id_prepararDocs' => $preparar->id];
            $flujo2 = Flujo2::create($data);

            $matrimonio = Matrimonio::find($request->input('id_matrimonio'));
            $flujo2->preparacionDocumentos()->associate($preparar);
            $flujo2->matrimonio()->associate($matrimonio);

            return response()->json($flujo2);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
