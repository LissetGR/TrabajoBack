<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Flujo2;
use Illuminate\Http\Request;

class flujo2Controller extends Controller
{
    public function getFlujo2(){
        try{
               $flujo=Flujo2::all();
               return response()->json($flujo);
           }catch(\Exception $e){
               return response()->json($e->getMessage());
           }

       }
}
