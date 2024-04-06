<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\llegada_Doc11;
use Illuminate\Http\Request;

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
}
