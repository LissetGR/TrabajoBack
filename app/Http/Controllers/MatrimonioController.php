<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatrimonioResource;
use App\Http\Resources\reciboResource;
use App\Models\Cliente;
use Illuminate\Support\Facades\Validator;
use App\Models\Flujo1;
use App\Models\Flujo2;
use App\Models\Flujo3;
use App\Models\Matrimonio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Rules\CamposPermitidos;
use function PHPUnit\Framework\isEmpty;

class MatrimonioController extends Controller
{
    public function getAllMatrimonios(Request $request){
        $limit = $request->input('limit', 10);
        try{

            $matrimonios=Matrimonio::paginate($limit);
            return response()->json(MatrimonioResource::collection($matrimonios->items()));
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function getMatrimonio(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['numero'])],
                'numero' => 'numeric|required'
            ]);

            $matrimonio = matrimonio::findOrFail($validator['numero']);
            return response()->json(new MatrimonioResource($matrimonio));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos para obtener el matrimonio',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function busquedaMatrimonio(Request $request)
    {
        try {
            $current_year = Carbon::now()->year;
            $hundred_years_ago = (new Carbon("100 years ago"))->year;

            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['nombre','pasaporte','numero','tipo','anno','mes','dia'])],
                'nombre' => 'string',
                'numero' => 'numeric',
                'pasaporte' => 'string|min:7|max:12|regex:/^[a-zA-Z].*$/',
                'tipo' => [
                     'string',
                    function ($attribute, $value, $fail) {
                        $allowedValues = ['Per procura', 'Congiunto'];
                        if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                            $fail($attribute . ' Campo no valido');
                        }
                    }
                ],
                'anno' => 'integer|between:' . $hundred_years_ago . ',' . $current_year,
                'mes' => 'integer|between:1,12',
                'dia' => 'integer|between:1,31',
            ]);

            $matrimonios = Matrimonio::query()
                ->when($request->has('nombre'), function ($query) use ($validator) {
                    $query->where(function ($query) use ($validator) {
                        $query->whereHas('usuario_italiano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(nombre_apellidos)'), 'LIKE',  strtolower($validator['nombre']) . '%');
                        })
                        ->orWhereHas('usuario_cubano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(nombre_apellidos)'), 'LIKE', '%' . strtolower($validator['nombre']) . '%');
                        });
                    });
                })
                ->when($request->has('pasaporte'), function ($query) use ($validator) {
                    $query->where(function ($query) use ($validator) {
                        $query->whereHas('usuario_italiano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(pasaporte)'), 'LIKE', '%' . strtolower($validator['pasaporte']) . '%');
                        })
                        ->orWhereHas('usuario_cubano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(pasaporte)'), 'LIKE', '%' . strtolower($validator['pasaporte']) . '%');
                        });
                    });
                })
                ->when($request->has('numero'), function ($query) use ($validator) {
                    return $query->where('numero', $validator['numero']);
                })
                ->when($request->has('tipo'), function ($query) use ($validator) {
                    return $query->where(DB::raw('lower(tipo)'), 'LIKE', '%' . strtolower($validator['tipo'] . '%'));
                })
                ->when($request->has('day'), function ($query) use ($validator) {
                    return $query->whereDay('fecha_llegada', $validator['day']);
                })
                ->when($request->has('mes'), function ($query) use ($validator) {
                    return $query->whereMonth('fecha_llegada', $validator['mes']);
                })
                ->when($request->has('anno'), function ($query) use ($validator) {
                    return $query->whereYear('fecha_llegada', $validator['anno']);
                })
                ->get();

            if ($matrimonios->isNotEmpty()) {
                return response()->json(MatrimonioResource::collection($matrimonios));
            } else {
                return response()->json([
                    'error' => 'Registro no encontrado',
                    'message' => 'No se pudo encontrar registros con los datos proporcionados',
                ], 404);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos proporcionados para la busqueda',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $error) {
            return response()->json($error->getMessage());
        }
    }


    public function create(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['username_cubano','username_italiano','numero','tipo','via_llegada','costo','fecha_llegada'])],
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


            $username_italiano = Cliente::
                where('es_cubano', false)
                ->where('clientes.username', $validator['username_italiano'])
                ->firstOr(function () {
                   throw new \Exception('No se pudo encontrar el cliente italiano con el username proporcionado');
                });

            $username_cubano = Cliente::
            where('es_cubano', true)
            ->whereRaw('LOWER(username) = :username', ['username' => strtolower($validator['username_cubano'])])
            ->firstOr(function () {
                throw new \Exception('No se pudo encontrar el cliente cubano con el username proporcionado');
            });

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
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos proporcionados para crear el registro matrimonio',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function modificar(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['username_cubano','username_italiano','numero','tipo','via_llegada','costo','fecha_llegada'])],
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

            $username_italiano =  Cliente::
                 where('es_cubano', false)
                ->where('clientes.username', $validator['username_italiano'])
                ->firstOr(function () {
                    throw new \Exception('Cliente italiano no encontrado');
                });

            $username_cubano = Cliente::
            where('es_cubano',true)
            ->whereRaw('LOWER(username) = ?', [strtolower($validator['username_cubano'])])
            ->firstOr(function () {
                throw new \Exception('Cliente cubano no encontrado');
            });



            $matrimonio = Matrimonio::findOrFail($validator['numero']);
            $matrimonio->update([
                'tipo' => Str::ucfirst($validator['tipo']),
                'username_cubano' => $username_cubano->id,
                'username_italiano' => $username_italiano->id,
                'via_llegada' => Str::ucfirst($validator['via_llegada']),
                'costo' => $validator['costo'],
                'fecha_llegada' => $validator['fecha_llegada']
            ]);
            return response()->json(new MatrimonioResource($matrimonio));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación de los datos para modificar el registro matrimonio',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make(
                ['numero' => $request->header('numero')],
                [
                    '*' => ['sometimes', new CamposPermitidos(['numero'])],
                    'numero' => 'required|numeric',
                ]
            );

            $matrimonio = Matrimonio::findOrFail($validator->getData()['numero']);
            $matrimonio->delete();
            return response()->json(new MatrimonioResource($matrimonio));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos proporcionados para eliminar el matrimonio',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function getPagos(Request $request)
    {
        try {
            $current_year = Carbon::now()->year;
            $hundred_years_ago = (new Carbon("100 years ago"))->year;

            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['numero', 'pasaporte', 'nombre','mes','anno','dia'])],
                'nombre' => 'string',
                'pasaporte' => 'string|min:7|max:12|regex:/^[a-zA-Z].*$/',
                'numero' => 'numeric',
                'anno' => 'integer|between:' . $hundred_years_ago . ',' . $current_year,
                'mes' => 'integer|between:1,12',
                'dia' => 'integer|between:1,31',
            ]);

            $matrimonios = Matrimonio::query()
                ->whereHas('forma_pago', function ($query) {
                    $query->whereRaw('matrimonios.costo <= forma_pagos.monto_pago');
                })
                ->when($request->has('nombre'), function ($query) use ($validator) {
                    $query->where(function ($query) use ($validator) {
                        $query->whereHas('usuario_italiano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(nombre_apellidos)'), 'LIKE', '%' . strtolower($validator['nombre']) . '%');
                        })
                        ->orWhereHas('usuario_cubano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(nombre_apellidos)'), 'LIKE', '%' . strtolower($validator['nombre']) . '%');
                        });
                    });
                })
                ->when($request->has('pasaporte'), function ($query) use ($validator) {
                    $query->where(function ($query) use ($validator) {
                        $query->whereHas('usuario_italiano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(pasaporte)'), 'LIKE', '%' . strtolower($validator['pasaporte']) . '%');
                        })
                        ->orWhereHas('usuario_cubano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(pasaporte)'), 'LIKE', '%' . strtolower($validator['pasaporte']) . '%');
                        });
                    });
                })
                ->when($request->has('numero'), function ($query) use ($validator) {
                    return $query->where('numero', $validator['numero']);
                })
                ->when($request->has('day'), function ($query) use ($validator) {
                    return $query->whereDay('fecha_llegada', $validator['day']);
                })
                ->when($request->has('mes'), function ($query) use ($validator) {
                    return $query->whereMonth('fecha_llegada', $validator['mes']);
                })
                ->when($request->has('anno'), function ($query) use ($validator) {
                    return $query->whereYear('fecha_llegada', $validator['anno']);
                })
                ->get();

            if ($matrimonios->isNotEmpty()) {
                return response()->json(MatrimonioResource::collection($matrimonios));
            } else {
                return response()->json([
                    'error' => 'Registro no encontrado',
                    'message' => 'No se pudo encontrar registros con los datos proporcionados',
                ], 404);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos proporcionados para la busqueda',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $error) {
            return response()->json($error->getMessage());
        }
    }

    public function getNoPagos(Request $request)
    {
        try {

            $current_year = Carbon::now()->year;
            $hundred_years_ago = (new Carbon("100 years ago"))->year;

            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['numero', 'pasaporte', 'nombre','mes','anno','dia'])],
                'nombre' => 'string',
                'numero' => 'numeric',
                'pasaporte' => 'string|min:7|max:12|regex:/^[a-zA-Z].*$/',
                'anno' => 'integer|between:' . $hundred_years_ago . ',' . $current_year,
                'mes' => 'integer|between:1,12',
                'dia' => 'integer|between:1,31',
            ]);

            $matrimonios = Matrimonio::query()
                ->whereHas('forma_pago', function ($query) {
                    $query->whereRaw('matrimonios.costo >= forma_pagos.monto_pago');
                })
                ->when($request->has('nombre'), function ($query) use ($validator) {
                    $query->where(function ($query) use ($validator) {
                        $query->whereHas('usuario_italiano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(nombre_apellidos)'), 'LIKE', '%' . strtolower($validator['nombre']) . '%');
                        })
                        ->orWhereHas('usuario_cubano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(nombre_apellidos)'), 'LIKE', '%' . strtolower($validator['nombre']) . '%');
                        });
                    });
                })
                ->when($request->has('pasaporte'), function ($query) use ($validator) {
                    $query->where(function ($query) use ($validator) {
                        $query->whereHas('usuario_italiano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(pasaporte)'), 'LIKE', '%' . strtolower($validator['pasaporte']) . '%');
                        })
                        ->orWhereHas('usuario_cubano', function ($query) use ($validator) {
                            $query->where(DB::raw('lower(pasaporte)'), 'LIKE', '%' . strtolower($validator['pasaporte']) . '%');
                        });
                    });
                })
                ->when($request->has('numero'), function ($query) use ($validator) {
                    return $query->where('numero', $validator['numero']);
                })
                ->when($request->has('day'), function ($query) use ($validator) {
                    return $query->whereDay('fecha_llegada', $validator['day']);
                })
                ->when($request->has('mes'), function ($query) use ($validator) {
                    return $query->whereMonth('fecha_llegada', $validator['mes']);
                })
                ->when($request->has('anno'), function ($query) use ($validator) {
                    return $query->whereYear('fecha_llegada', $validator['anno']);
                })
                ->get();

            if ($matrimonios->isNotEmpty()) {
                return response()->json(MatrimonioResource::collection($matrimonios));
            } else {
                return response()->json([
                    'error' => 'Registro no encontrado',
                    'message' => 'No se pudo encontrar registros con los datos proporcionados',
                ], 404);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos proporcionados para la busqueda',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $error) {
            return response()->json($error->getMessage());
        }
    }


    public function getAllFlujos(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['numero'])],
                'id' => 'numeric|required'
            ]);

            $flujo1 = Flujo1::where('id_matrimonio', $validator['numero'])->first();
            $flujo2 = Flujo2::where('id_matrimonio', $validator['numero'])->first();
            $flujo3 = Flujo3::where('id_matrimonio', $validator['numero'])->first();

            if (isset($flujo1)) {
                $respuesta = [
                    'flujo1' => $flujo1,
                ];
            }
            if (isset($flujo2)) {
                $respuesta = $respuesta + [
                    "flujo2" => $flujo2,
                ];
            }
            if (isset($flujo3)) {
                $respuesta = $respuesta + [
                    "flujo3" => $flujo3,
                ];
            }
            return response()->json($respuesta);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos proporcionados para obtener los datos de los flujos',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function getRecibos(Request $request){
        $limit = $request->input('limit', 10);
        try{
            $matrimonios=Matrimonio::with('forma_pago.cuotas')->paginate($limit);
            return response()->json(reciboResource::collection($matrimonios->items()));
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
