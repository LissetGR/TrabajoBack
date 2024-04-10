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

use function PHPUnit\Framework\isEmpty;

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


            $username_italiano =Cliente::has('cliente_italiano')
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

            $matrimonio->usuario_italiano()->associate($username_italiano);
            $matrimonio->usuario_cubano()->associate($username_cubano);


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


    public function getProcesosPorMes(Request $request){
      try{
            $matrimonios=Matrimonio::query()
            ->when($request->has('day'), function ($query) use ($request) {
                return $query->whereDay('fecha_llegada', $request->input('day'));
            })
            ->when($request->has('mes'), function ($query) use ($request) {
                return $query->whereMonth('fecha_llegada', $request->input('mes'));
            })
            ->when($request->has('anno'), function ($query) use ($request) {
                return $query->whereYear('fecha_llegada', $request->input('anno'));
            })
            ->get();

            if($matrimonios->isNotEmpty()){
                return response()->json(MatrimonioResource::collection($matrimonios));
            }else{
                return response()->json('No hay registros en esa fecha');
            }


      }catch(\Exception $e){
        return response()->json($e->getMessage());
      }
    }


    public function getPagos(Request $request){
        try{

            $matrimonios = Matrimonio::whereHas('forma_pago', function ($query) {
                $query->where('tipo', 'Pagato totale');
            })
            ->orWhereHas('forma_pago', function ($query) {
                $query->where('tipo', 'Acconto')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('matrimonios')
                          ->whereRaw('matrimonios.costo = forma_pagos.monto_pago');
                    });
            })
            ->when($request->has('day'), function ($query) use ($request) {
                return $query->whereDay('fecha_llegada', $request->input('day'));
            })
            ->when($request->has('mes'), function ($query) use ($request) {
                return $query->whereMonth('fecha_llegada', $request->input('mes'));
            })
            ->when($request->has('anno'), function ($query) use ($request) {
                return $query->whereYear('fecha_llegada', $request->input('anno'));
            })
            ->get();

            return response()->json(MatrimonioResource::collection($matrimonios));
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function getNoPagos(Request $request){
        try{

            $matrimonios = Matrimonio::whereDoesntHave('forma_pago', function ($query) {
                $query->where('tipo', 'Pagato totale');
            })
            ->orWhereHas('forma_pago', function ($query) {
                $query->where('tipo', 'Acconto')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('matrimonios')
                          ->whereRaw('matrimonios.costo != forma_pagos.monto_pago');
                    });
            })
            ->when($request->has('day'), function ($query) use ($request) {
                return $query->whereDay('fecha_llegada', $request->input('day'));
            })
            ->when($request->has('mes'), function ($query) use ($request) {
                return $query->whereMonth('fecha_llegada', $request->input('mes'));
            })
            ->when($request->has('anno'), function ($query) use ($request) {
                return $query->whereYear('fecha_llegada', $request->input('anno'));
            })
            ->get();

            return response()->json(MatrimonioResource::collection($matrimonios));
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

}
