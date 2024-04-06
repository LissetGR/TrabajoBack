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
}
