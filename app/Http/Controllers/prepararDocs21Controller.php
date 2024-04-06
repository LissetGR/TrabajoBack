<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\preparar_Doc21;
use Illuminate\Http\Request;

class prepararDocs21Controller extends Controller
{
    public function getPreparar()
    {
        try {
            $preparar = preparar_Doc21::all();
            return response()->json($preparar);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
