<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatrimonioResource;
use App\Models\Cliente;
use App\Models\ClienteItaliano;
use App\Models\Matrimonio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MatrimonioController extends Controller
{
    public function getMatrimonio()
    {
        try {
            $matrimonio = matrimonio::all();
            return response()->json(MatrimonioResource::collection($matrimonio));
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function create(Request $request)
    {
        try {
            $validator = $request->validate([
                'numero' => 'required|numeric|unique:matrimonios',
                'username_italiano' => 'required|string',
                'username_cubano' => 'required|string',
                'tipo' => [
                    'required', 'string',
                    function ($attribute, $value, $fail) {
                        $allowedValues = ['Per procura', 'Congiunto'];
                        if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                            $fail($attribute . ' Campo no valido');
                        }
                    }
                ],
                'via_llegada' => [
                    'required', 'string',
                    function ($attribute, $value, $fail) {
                        $allowedValues = ['Mail', 'Chiamata', 'Whatsapp', 'In busta'];
                        if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                            $fail($attribute . 'Campo no valido');
                        }
                    }
                ],
                'costo' => 'required|numeric',
                'fecha_llegada' => 'required|date_format:d/m/Y'
            ]);


            $username_italiano =  Cliente::join('cliente_italianos', 'clientes.id', '=', 'cliente_italianos.id')
                ->where('clientes.username', $validator['username_italiano'])
                ->first();

            $username_cubano = Cliente::whereRaw('LOWER(username) = ?', [strtolower($validator['username_cubano'])])->first();

            $matrimonio = matrimonio::create([
                'numero' => $validator['numero'],
                'tipo' => Str::ucfirst($validator['tipo']),
                'username_cubano' => $username_cubano->id,
                'username_italiano' => $username_italiano->id,
                'via_llegada' => Str::ucfirst($validator['via_llegada']),
                'costo' => $validator['costo'],
                'fecha_llegada' => $validator['fecha_llegada']
            ]);


            return response()->json(new MatrimonioResource($matrimonio));
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function modificar(Request $request)
    {
        try {
            $validator = $request->validate([
                'numero' => 'required|numeric',
                'username_italiano' => 'required|string',
                'username_cubano' => 'required|string',
                'tipo' => [
                    'required', 'string',
                    function ($attribute, $value, $fail) {
                        $allowedValues = ['Per procura', 'Congiunto'];
                        if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                            $fail($attribute . ' Campo no valido');
                        }
                    }
                ],
                'via_llegada' => [
                    'required', 'string',
                    function ($attribute, $value, $fail) {
                        $allowedValues = ['Mail', 'Chiamata', 'Whatsapp', 'In busta'];
                        if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                            $fail($attribute . 'Campo no valido');
                        }
                    }
                ],
                'costo' => 'required|numeric',
                'fecha_llegada' => 'required|date_format:d/m/Y'
            ]);

            $username_italiano =  Cliente::join('cliente_italianos', 'clientes.id', '=', 'cliente_italianos.id')
            ->where('clientes.username', $validator['username_italiano'])
            ->first();

            $username_cubano = Cliente::whereRaw('LOWER(username) = ?', [strtolower($validator['username_cubano'])])->first();


            try {
                $matrimonio = Matrimonio::find($validator['numero']);
                $matrimonio->update([
                    'tipo' => $validator['tipo'],
                    'username_cubano' => $username_cubano->id,
                    'username_italiano' => $username_italiano->id,
                    'via_llegada' => $validator['via_llegada'],
                    'costo' => $validator['costo'],
                    'fecha_llegada' => $validator['fecha_llegada']
                ]);
            } catch (\Exception $e) {
                return response()->json($e->getMessage());
            }



            return response()->json(new MatrimonioResource($matrimonio));
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $matrimonio = Matrimonio::findOrFail($request->input('numero'));
            $matrimonio->delete();
            return response()->json(new MatrimonioResource($matrimonio));
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
