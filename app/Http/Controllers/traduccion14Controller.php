<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\traduccion14;
use Illuminate\Http\Request;

class traduccion14Controller extends Controller
{
    public function getTraduccion()
    {
        try {
            $traduccion = traduccion14::all();
            return response()->json($traduccion);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
