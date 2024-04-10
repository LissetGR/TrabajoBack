<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\preparar_Doc21;
use Illuminate\Http\Request;

class prepararDocs21Controller extends Controller
{
    public function getPreparar()
    {
        try {
            $preparar = preparar_Doc21::all();
            return response()->json($preparar);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function create(Request $request){
        try{
            $validator= $request->validate([
                'doc_provItalia21'=>'required|date|date_format:d/m/Y',
                'solicitud_Trans'=>'required|boolean',
                'delegacion'=>'required|boolean',
                'certificado_residencia'=>'required|boolean',
                'doc_idItaliano'=>'required|boolean',
            ]);

            $preparar=preparar_Doc21::create($validator);

            return response()->json($preparar);

        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }


    public function destroy(Request $request){
        try{
           $preparar=preparar_Doc21::findOrFail($request->input('id'));
           $preparar->delete();


           return response()->json($preparar);
        }catch(\Exception $e){
            return response()->json($e);
        }
    }
}
