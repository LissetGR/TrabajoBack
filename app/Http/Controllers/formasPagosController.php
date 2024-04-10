<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\formaPago;
use App\Models\Matrimonio;
use Illuminate\Http\Request;

class formasPagosController extends Controller
{
    public function getFormaPago()
    {
        try {
            $forma = formaPago::all();
            return response()->json($forma);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function create(Request $request){
        try{
           $validator=$request->validate([
            'id_matrimonio'=>'required|numeric',
            'tipo'=>'required','string',
            function ($attribute, $value, $fail) {
                $allowedValues = ['Pagato totale', 'Acconto'];
                if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                    $fail($attribute . ' Campo no valido');
                }
            },
            'montoPago'=>'required|numeric',
            'fecha'=>'required|date|date_format:d/m/Y',
           ]);

           $matrimonio=Matrimonio::find($request->input('id_matrimonio'));
           $forma=formaPago::create($validator);

           $forma->matrimonio()->associate($forma);

           return response()->json($forma);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
