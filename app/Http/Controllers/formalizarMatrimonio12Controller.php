<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\formalizar_Matrim12;
use Illuminate\Http\Request;

class formalizarMatrimonio12Controller extends Controller
{
    public function getFormalizar(){
        try{
               $form=formalizar_Matrim12::all();
               return response()->json($form);
           }catch(\Exception $e){
               return response()->json($e->getMessage());
           }
    }

    public function create(Request $request){

        try{

            $validator= $request->validate([
                'fecha'=>'required|date|date_format:d/m/Y',
                'lugar'=>'required|string|',
                'tipo'=>'required','string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Divizioni dei beni', ' Comunidad dei beni'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                },
            ]);

            $formalizar=formalizar_Matrim12::create([
                'fecha'=>$validator['fecha'],
                'lugar'=>$validator['lugar'],
                'tipo'=>$validator['tipo'],
            ]);

            return response()->json($formalizar);

        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function destroy(Request $request){
        try{
           $formalizar=formalizar_Matrim12::findOrFail($request->input('id'));
           $formalizar->delete();

           return response()->json($formalizar);
        }catch(\Exception $e){
            return response()->json($e);
        }
    }
}
