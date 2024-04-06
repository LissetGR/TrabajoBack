<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\observaciones;
use Illuminate\Http\Request;

class observacionesController extends Controller
{
    public function getObservaciones()
    {
        try {
            $observaciones = observaciones::all();
            return response()->json($observaciones);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
