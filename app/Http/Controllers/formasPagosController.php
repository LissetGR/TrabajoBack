<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\formaPago;
use Illuminate\Http\Request;

class formasPagosController extends Controller
{
    public function getFormaPago()
    {
        try {
            $forma = formaPago::all();
            return response()->json($forma);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
