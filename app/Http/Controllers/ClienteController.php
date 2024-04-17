<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClienteItalianoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\cliente;
use App\Models\ClienteItaliano;

class ClienteController extends Controller
{
    public function getCliente()
    {
        $clientes=Cliente::doesntHave('cliente_italiano')->get();
        return response()->json($clientes);
    }

    public function getAllCliente()
    {
        $clientes = Cliente::doesntHave('cliente_italiano')->get();
        $clienteItaliano = ClienteItaliano::all();

        $respuesta = [
            'clientes cubanos' => $clientes,
            'clientes italianos' => ClienteItalianoResource::collection($clienteItaliano)
        ];
        return response()->json($respuesta);
    }


    public function getClienteById(Request $request)
    {
        try {
            $cliente = cliente::with('matrimonio')->find($request->input('id'));
            return response()->json($cliente);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function busquedaClientes(Request $request){
        try{
            $clientes=cliente::query()
            ->when($request->has('nombre'), function ($query) use ($request) {
                return $query->where('nombre_apellidos','LIKE', '%'. $request->input('nombre').'%');
            })
            ->when($request->has('username'), function ($query) use ($request) {
                return $query->where('username','LIKE', '%'.$request->input('username').'%');
            })
            ->when($request->has('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->with('cliente_italiano')
            ->get();

            if($clientes->isNotEmpty()){
                return response()->json($clientes);
            }else{
                return response()->json('No hay registros con esos datos');
            }
        }catch(\Exception $error){
            return response()->json($error->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $validator = $request->validate([
                'username' => 'required|string|min:8|max:100|alpha_dash|unique:clientes',
                'nombre_apellidos' => 'required|string|min:10',
                'direccion' => 'required|string|min:10',
                'telefono' => 'required|numeric|min:8',
                'email' => 'required|email'
            ]);

            $cliente = cliente::create([
                'username' => $validator['username'],
                'nombre_apellidos' => $validator['nombre_apellidos'],
                'direccion' => $validator['direccion'],
                'telefono' => $validator['telefono'],
                'email' => $validator['email']
            ]);

            $cliente->save();

            return response()->json($cliente);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $cliente = cliente::findOrFail($request->input('id'));
            $cliente->delete();
            return response()->json($cliente);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function modificar(Request $request)
    {
        try {
            $validator = $request->validate([
                'username' => 'required|string|min:8|max:100|alpha_dash',
                'nombre_apellidos' => 'required|string|min:10',
                'direccion' => 'required|string|min:10',
                'telefono' => 'required|numeric|min:8',
                'email' => 'required|email'
            ]);

            $cliente = cliente::findOrFail($request->input('id'));
            $cliente->update($validator);
            return response()->json($cliente);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
