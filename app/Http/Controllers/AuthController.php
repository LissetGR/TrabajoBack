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

class AuthController extends Controller
{



    public function register( Request $request){
        // try{
            $validator = $request->validate( [
                'name' => 'required|string|min:8|max:100|alpha_dash|unique:users',
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'role'=> ['required',Rule::in(['Admin', 'Trabajador', 'Cliente'])],
            ]);


            $user=User::create([
                'name'=> $validator['name'],
                'password'=> Hash::make($validator['password']),
                'role'=>$validator['role']
            ])->assignRole($validator['role']);

            $user->save();

            Auth::login($user);

            return response()->json([
                'token'=> $user-> createToken('userToke')-> plainTextToken,
            ],200);

        // }catch(ValidationException $error){
        //     return back()->with('error', $error->getMessage());
        // };

    }


    public function login(Request $request){
    //    try{
            $validator= $request->validate([
                'name'=> 'required|string|max:100|',
                'password' => 'required|string|min:8'
            ]);


            $user = User::where('name', $request->name)->first();

            if(Auth::attempt($validator,true)){
                return response()->json([
                    'token'=> $user-> createToken('userToke')-> plainTextToken,
                ],200);
            }

            return back()->with('error', 'Usuario o contraseña equivocada');

    //    }catch(ValidationException $error){
    //         return back()->with('error', $error->getMessage());
    //    }
    }


    public function logout(Request $request){
        try{
          $request->user()->currentAccessToken()->delete();

          return response()->json([
            'status'=>true
          ]);

        }catch(ValidationException $error){
            return back()->with('error', $error->getMessage());
       }
    }


    public function modificar(Request $request){

        $validator = $request->validate( [
            'name' => 'required|string|max:100|alpha_dash|unique:users',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ]);

        $user=User::findOrFail($request->input('id'));
        $user->update($request->all());
        $user->save();

        return response()->json([
            'status'=>true
        ],200);
    }

    public function updatePassword(Request $request){

        $validator = $request->validate( [
            'current_password' => 'required|string|current_password',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ]);

        $request->user()->update([
            'password'=> Hash::make($validator['password']),
        ]);

        return response()->json([
            'status'=>true
        ],200);
    }



}
