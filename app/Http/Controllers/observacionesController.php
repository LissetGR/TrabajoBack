<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\observaciones;
use App\Models\Matrimonio;
use Illuminate\Http\Request;

class observacionesController extends Controller
{
    public function getObservaciones()
    {
        try {
            $observaciones = observaciones::all();
            return response()->json($observaciones);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function create(Request $request){
        try{
           $validator=$request->validate([
            'id_matrimonio'=>'required|numeric',
            'descripcion'=>'required|string'
           ]);

           $matrimonio=Matrimonio::find($request->input('id_matrimonio'));
           $observaciones=observaciones::create($validator);

           $observaciones->matrimonio()->associate($matrimonio);

           return response()->json($observaciones);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }


    public function destroy(Request $request){
        try{
           $observaciones=observaciones::findOrFail($request->input('id'));
           $observaciones->delete();

           return response()->json($observaciones);
        }catch(\Exception $e){
            return response()->json($e);
        }
    }
}
