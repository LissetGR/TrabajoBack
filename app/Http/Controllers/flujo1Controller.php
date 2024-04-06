<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Flujo1;
use Illuminate\Http\Request;

class flujo1Controller extends Controller
{
    public function getFlujo1(){
        try{
               $flujo=Flujo1::all();
               return response()->json($flujo);
           }catch(\Exception $e){
               return response()->json($e->getMessage());
           }

       }
}
