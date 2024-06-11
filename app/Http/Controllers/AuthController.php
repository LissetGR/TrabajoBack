<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function PHPUnit\Framework\throwException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:5,1')->only('login');
    }

    public function register(Request $request)
    {
        try {
            $validator = $request->validate([
                'name' => 'required|string|min:8|max:100|unique:users',
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'role' => ['required', Rule::in(['Admin', 'Trabajador', 'Cliente'])],
            ]);


            $user = User::create([
                'name' => $validator['name'],
                'password' => Hash::make($validator['password']),
                'role' => $validator['role']
            ])->assignRole($validator['role']);

            $user->save();

            Auth::login($user);

            return response()->json([
                'token' => $user->createToken('userToke')->plainTextToken,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación en los datos del usuario',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $error) {
            return response()->json([
                'error' => 'Error inesperado',
                'message' => $error->getMessage(),
            ], 500);
        };
    }


    public function login(Request $request)
    {
        try {
            $validator = $request->validate([
                'name' => 'required|string|max:100|',
                'password' => 'required|string|min:8'
            ]);


            $user = User::where('name', $request->name)->first();
             
            if (Auth::attempt($validator, true)) {
                return response()->json([
                    'token' => $user->createToken('userToke')->plainTextToken,
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Credenciales incorrectas o usuario no existente',
                    'message' => 'Por favor, verifica tu nombre de usuario y contraseña',
                ], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $error) {
            return response()->json([
                'error' => 'Error inesperado',
                'message' => $error->getMessage(),
            ], 500);
        };
    }


    public function logout(Request $request)
    {
        try {
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Cierre de sesión exitoso',
                ]);
            } else {
                return response()->json([
                    'error' => 'Usuario no autenticado',
                    'message' => 'No se pudo cerrar la sesión porque el usuario no está autenticado',
                ], 401);
            }
        } catch (\Exception $error) {
            return response()->json([
                'error' => 'Error inserperado',
                'mensaje' => $error->getMessage(),
            ], 500);
        }
    }


    public function modificar(Request $request)
    {
        try {
            $validator = $request->validate([
                'name' => 'required|string|max:100|alpha_dash',
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ]);

            $user = User::findOrFail($validator['id']);
            $user->update($validator);

            return response()->json([
                'status' => true
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación en los datos del usuario',
                'message' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Usuario no encontrado',
                'message' => 'No se pudo encontrar el usuario con el ID proporcionado',
            ], 404);
        } catch (\Exception $error) {
            return response()->json([
                'error' => 'Error inesperado',
                'message' => $error->getMessage(),
            ], 500);
        };
    }

    public function updatePassword(Request $request)
    {
        try {
            $validator = $request->validate([
                'current_password' => 'required|string|current_password',
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ]);

            $request->user()->update([
                'password' => Hash::make($validator['password']),
            ]);

            return response()->json([
                'status' => true
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $error) {
            return response()->json([
                'error' => 'Error inesperado',
                'message' => $error->getMessage(),
            ], 500);
        };
    }


    public function getUser(Request $request)
    {
        try {
            $users = User::all();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los usuarios'], 500);
        }
    }
}
