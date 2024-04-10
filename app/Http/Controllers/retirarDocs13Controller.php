<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\retirar_Doc13;
use Illuminate\Http\Request;

class retirarDocs13Controller extends Controller
{
    public function getRetirar()
    {
        try {
            $retirar = retirar_Doc13::all();
            return response()->json($retirar);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function create(Request $request){
       try{
            $validator=$request->validate([
                'fecha_Procura'=>'required|date|date_format:d/m/Y',
                'fecha_Matrimonio'=>'required|date|date_format:d/m/Y'
            ]);

            $retiro=retirar_Doc13::create([
                'fecha_Procura'=>$validator['fecha_Procura'],
                'fecha_Matrimonio'=>$validator['fecha_Matrimonio']
            ]);

            return response()->json($retiro);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }

    }


    public function destroy(Request $request){
        try{
           $retirar=retirar_Doc13::findOrFail($request->input('id'));
           $retirar->delete();


           return response()->json($retirar);
        }catch(\Exception $e){
            return response()->json($e);
        }
    }
}
