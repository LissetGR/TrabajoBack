<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cuotas;
use App\Models\formaPago;

class cuotasController extends Controller
{
    public function getCuotas(Request $request){
        try{
            $cuotas= cuotas::where('id_formaPago',$request->input('id'))->get();
            return response()->json($cuotas);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function create(Request $request){
        try{
            $validator=$request->validate([
               'id_formaPago'=>'required|numeric',
               'cantidad'=>'required|numeric',
               'fecha'=>'required|date'
            ]);

            $cuota=cuotas::create([
                'id_formaPago'=>$validator['id_formaPago'],
                'cantidad'=>$validator['cantidad'],
                'fecha'=>$validator['fecha']
            ]);

            $cuota->forma_pago->update([
               'monto_pago'=> $cuota->forma_pago->monto_pago+$cuota->cantidad,
            ]);

            return response()->json($cuota);

        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }


    public function destroy(Request $request){
        try{
           $cuota=cuotas::findOrFail($request->input('id'));
           $cuota->delete();

           return response()->json($cuota);
        }catch(\Exception $e){
            return response()->json($e);
        }
    }
}
