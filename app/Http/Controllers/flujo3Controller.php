<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flujo3;
use App\Models\Matrimonio;

class flujo3Controller extends Controller
{
    public function getFlujo3(){
        try{
               $flujo=Flujo3::all();
               return response()->json($flujo);
           }catch(\Exception $e){
               return response()->json($e->getMessage());
           }

    }

    public function create(Request $request)
    {

        $prepararDocs = app('App\Http\Controllers\prepararDocs31Controller');

        try {
            $validator = $request->validate([
                'id_matrimonio' => 'required|unique:flujo3s|numeric',
                'cita_cubano' => 'nullable|date|date_format:d/m/Y',
                'solicitud_visado' => 'nullable|date|date_format:d/m/Y',
                'retiro_passport' => 'nullable|date|date_format:d/m/Y',
                'ultimo_Email' => 'nullable|date|date_format:d/m/Y',
            ]);

            $preparar = $prepararDocs->create($request);
            $preparar = json_encode($preparar->getData());
            $preparar = json_decode(($preparar));

            // if($request->has(''))

            $data = $validator + ['id_prepararDocs' => $preparar->id];
            $flujo3 = Flujo3::create($data);

            $matrimonio = Matrimonio::find($request->input('id_matrimonio'));
            $flujo3->preparacionDocumentos()->associate($preparar);
            $flujo3->matrimonio()->associate($matrimonio);

            return response()->json($flujo3);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
