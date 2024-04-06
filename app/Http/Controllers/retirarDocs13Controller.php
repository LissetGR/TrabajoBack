<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\retirar_Doc13;
use Illuminate\Http\Request;

class retirarDocs13Controller extends Controller
{
    public function getRetirar()
    {
        try {
            $retirar = retirar_Doc13::all();
            return response()->json($retirar);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
