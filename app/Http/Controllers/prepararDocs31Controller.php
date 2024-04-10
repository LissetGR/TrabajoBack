<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\preparar_Docs31;
use Illuminate\Http\Request;

class prepararDocs31Controller extends Controller
{
    public function getPreparar()
    {
        try {
            $preparar = preparar_Docs31::all();
            return response()->json($preparar);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function create(Request $request){
        try{
            $validator= $request->validate([
                'doc_provItalia31'=>'required|date|date_format:d/m/Y',
                'declaracion_alojamiento'=>'required|boolean',
                'reserva_aerea'=>'required|boolean',
                'certificado_residenciaItaliano'=>'required|boolean',
            ]);

            $preparar=preparar_Docs31::create($validator);

            return response()->json($preparar);

        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
