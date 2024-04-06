<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\preparar_Docs31;
use Illuminate\Http\Request;

class prepararDocs31Controller extends Controller
{
    public function getPreparar()
    {
        try {
            $preparar = preparar_Docs31::all();
            return response()->json($preparar);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
