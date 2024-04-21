<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClienteItalianoResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\cliente;
use App\Models\ClienteItaliano;
use Illuminate\Validation\ValidationException;
use App\Rules\CamposPermitidos;
use App\Models\User;

class ClienteController extends Controller
{
    public function getCliente()
    {
        try{
            $clientes=Cliente::doesntHave('cliente_italiano')->get();
            return response()->json($clientes);

        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }

    }

    public function getAllCliente(Request $request)
    {
        $limit = $request->input('limit', 10);

        try{
            $clientes = Cliente::doesntHave('cliente_italiano')->paginate($limit);
            $clienteItaliano = ClienteItaliano::paginate($limit);

            $respuesta = [
                'clientes cubanos' => $clientes->items(),
                'clientes italianos' => ClienteItalianoResource::collection($clienteItaliano)
            ];
            return response()->json($respuesta);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }

    }


    public function getClienteById(Request $request)
    {
        try {
            $validator=$request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'id'=>'required|numeric'
            ]);

            $cliente = cliente::with('matrimonio')->findOrFail($validator['id']);
            return response()->json($cliente);
        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Usuario no encontrado',
                'message' => 'No se pudo encontrar el usuario con el ID proporcionado',
            ], 404);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function busquedaClientes(Request $request){
        try{
            $validator=$request->validate([
                '*' => ['sometimes', new CamposPermitidos(['nombre','pasaporte', 'username', 'id'])],
                'pasaporte' => 'sometimes|string|alpha_dash',
                'username' => 'sometimes|string|alpha_dash',
                'nombre' => 'sometimes|string',
                'id'=>'sometimes|numeric'
            ]);

            $clientes=cliente::query()
            ->when($request->has('nombre'), function ($query) use ($validator) {
                return $query->whereRaw('LOWER(nombre_apellidos) LIKE ?', ['%' . strtolower($validator['nombre']) . '%']);
            })
            ->when($request->has('username'), function ($query) use ($validator) {
                return $query->whereRaw('LOWER(username) LIKE ?', ['%' . strtolower($validator['nombre']) . '%']);
            })
            ->when($request->has('pasaporte'), function ($query) use ($validator) {
                return $query->whereRaw('LOWER(pasaporte) LIKE ?', ['%' . strtolower($validator['pasaporte']) . '%']);
            })
            ->when($request->has('id'), function ($query) use ($validator) {
                return $query->where('id', $validator['id']);
            })
            ->with('cliente_italiano')
            ->get();

            if($clientes->isNotEmpty()){
                return response()->json($clientes);
            }else{
                return response()->json([
                    'error' => 'Usuario no encontrado',
                    'message' => 'No se pudo encontrar registros con los datos proporcionados',
                ], 404);
            }
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        }
        catch(\Exception $error){
            return response()->json($error->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {

            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['nombre_apellidos','pasaporte', 'username', 'direccion','telefono','email'])],
                'username' => 'required|string|min:8|max:100|alpha_dash|unique:clientes',
                'pasaporte' => 'required|string|min:7|max:7|alpha_dash|unique:clientes',
                'nombre_apellidos' => 'required|string|min:10',
                'direccion' => 'required|string|min:10',
                'telefono' => 'required|numeric|min:8',
                'email' => 'required|email'
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
                'nombre_apellidos' => $validator['nombre_apellidos'],
                'pasaporte'=>$validator['pasaporte'],
                'direccion' => $validator['direccion'],
                'telefono' => $validator['telefono'],
                'email' => $validator['email']
            ]);

            $cliente->save();

            return response()->json($cliente);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validator= $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
               'id'=>'required|numeric'
            ]);
            $cliente = cliente::findOrFail($validator['id']);
            $cliente->delete();
            return response()->json($cliente);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Usuario no encontrado',
                'message' => 'No se pudo encontrar el usuario con el ID proporcionado',
            ], 404);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function modificar(Request $request)
    {
        try {
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id','pasaporte','nombre_apellidos', 'username', 'direccion','telefono','email'])],
                'id'=>'required|numeric',
                'pasaporte' => 'required|string|min:7|max:7|alpha_dash',
                'username' => 'required|string|min:8|max:100|alpha_dash',
                'nombre_apellidos' => 'required|string|min:10',
                'direccion' => 'required|string|min:10',
                'telefono' => 'required|numeric|min:8',
                'email' => 'required|email'
            ]);

            $cliente = cliente::findOrFail($request->input('id'));
            $cliente->update($validator);
            return response()->json($cliente);
        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Usuario no encontrado',
                'message' => 'No se pudo encontrar el usuario con el ID proporcionado',
            ], 404);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
