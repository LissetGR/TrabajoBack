<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\traduccion14;
use Illuminate\Http\Request;

class traduccion14Controller extends Controller
{
    public function getTraduccion()
    {
        try {
            $traduccion = traduccion14::all();
            return response()->json($traduccion);
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

             $traduccion=traduccion14::create([
                 'fecha_Procura'=>$validator['fecha_Procura'],
                 'fecha_Matrimonio'=>$validator['fecha_Matrimonio']
             ]);

             return response()->json($traduccion);
         }catch(\Exception $e){
             return response()->json($e->getMessage());
         }

     }
}
