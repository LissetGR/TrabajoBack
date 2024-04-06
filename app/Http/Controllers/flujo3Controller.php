<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flujo3;

class flujo3Controller extends Controller
{
    public function getFlujo3(){
        try{
               $flujo=Flujo3::all();
               return response()->json($flujo);
           }catch(\Exception $e){
               return response()->json($e->getMessage());
           }

       }
}
