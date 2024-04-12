<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\llegada_Doc11;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class llegadaDocs11Controller extends Controller
{
     public function getllegadaDoc()
    {
        try {
            $llegada = llegada_Doc11::all();
            return response()->json($llegada);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function create(Request $request){
        try{

            $validator= $request->validate([
                'fecha'=>'required|date|date_format:d/m/Y',
                'doc1'=>'required','string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Cert. di nascita', 'Procura'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                },
                'doc2'=>'required','string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Stato libero', 'Sentenza di divorzio','Atto di morte'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                }
            ]);

            $llegada=llegada_Doc11::create([
                'fecha'=>$validator['fecha'],
                'doc1'=>Str::ucfirst($validator['doc1']),
                'doc2'=>Str::ucfirst($validator['doc2']),
            ]);

            return response()->json($llegada);

        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function modificar(Request $request){
        try{

            $validator= $request->validate([
                'fecha'=>'required|date|date_format:d/m/Y',
                'doc1'=>'required','string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Cert. di nascita', 'Procura'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                },
                'doc2'=>'required','string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Stato libero', 'Sentenza di divorzio','Atto di morte'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                }
            ]);

            $llegada=llegada_Doc11::findOrFail($request->input(('id')));

            $llegada->update($validator);

            return response()->json($llegada);

        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function destroy(Request $request){
        try{
           $llegada=llegada_Doc11::findOrFail($request->input('id'));
           $llegada->delete();


           return response()->json($llegada);
        }catch(\Exception $e){
            return response()->json($e);
        }
    }
}
