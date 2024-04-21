<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClienteItalianoResource;
use App\Models\ClienteItaliano;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\cliente;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Rules\CamposPermitidos;

class ClienteItalianoController extends Controller
{
    public function getClienteItaliano()
    {
        try {
            $clientes = ClienteItaliano::all();
            return response()->json(ClienteItalianoResource::collection($clientes));
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function getClienteItalianoById(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'id' => 'required|numeric'
            ]);

            $cliente = ClienteItaliano::with('matrimonio')->findOrFail($validator['id']);
            return response()->json(new ClienteItalianoResource($cliente));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Usuario no encontrado',
                'message' => 'No se pudo encontrar el usuario con el ID proporcionado'+ $e->getMessage(),
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function create(Request $request)
    {

        try {

            DB::beginTransaction();

            try {
                $validator = $request->validate([
                    '*' => ['sometimes', new CamposPermitidos(['nombre_apellidos', 'username', 'direccion','telefono','email', 'email_registro'])],
                    'username' => 'required|string|min:8|max:100|alpha_dash|unique:clientes',
                    'pasaporte' => 'required|string|min:7|max:7|alpha_dash|unique:clientes',
                    'nombre_apellidos' => 'required|string|min:10',
                    'direccion' => 'required|string|min:10',
                    'telefono' => 'required|numeric|min:8',
                    'email' => 'required|email',
                    'email_registro' => 'required|email',
                ]);

                $userExists = User::where('name', $request->input('username'))->exists();
                if (!$userExists) {
                    return response()->json([
                        'error' => 'Usuario no encontrado',
                        'message' => 'No se pudo encontrar el usuario con el username proporcionado',
                    ], 404);
                }


                $cliente = cliente::create([
                    'username' => $validator['username'],
                    'pasaporte'=>$validator['pasaporte'],
                    'nombre_apellidos' => $validator['nombre_apellidos'],
                    'direccion' => $validator['direccion'],
                    'telefono' => $validator['telefono'],
                    'email' => $validator['email']
                ]);

                $cliente_italiano = ClienteItaliano::create([
                    'id' => $cliente->id,
                    'email_registro' => $validator['email_registro'],
                ]);

                DB::commit();

                return response()->json(new ClienteItalianoResource($cliente_italiano));
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            };
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
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
                '*' => ['sometimes', new CamposPermitidos(['id','nombre_apellidos', 'username', 'direccion','telefono','email'])],
                'id' => 'required|numeric',
                'username' => 'required|string|min:8|max:100|alpha_dash',
                'nombre_apellidos' => 'required|string|min:10',
                'direccion' => 'required|string|min:10',
                'telefono' => 'required|numeric|min:8',
                'email' => 'required|email',
                'email_registro' => 'required|email',
            ]);


            $cliente = cliente::findOrFail($request->input('id'));
            $cliente_italiano = ClienteItaliano::findOrFail($request->input('id'));
            $cliente->update($validator);
            $cliente_italiano->update([
                'email_registro' => $validator['email_registro']
            ]);

            return response()->json(new ClienteItalianoResource($cliente_italiano));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Usuario no encontrado',
                'message' => 'No se pudo encontrar el usuario con el ID proporcionado'+ $e->getMessage(),
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
