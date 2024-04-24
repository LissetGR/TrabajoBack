<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\formalizar_Matrim12;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Rules\CamposPermitidos;

class formalizarMatrimonio12Controller extends Controller
{
    public function getFormalizar(Request $request){
        try{
            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'id' => 'required|numeric'
            ]);

               $form=formalizar_Matrim12::findOrFail($validator['id']);
               return response()->json($form);
           }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
            }catch (ValidationException $e) {
                return response()->json([
                    'error' => 'Error de validación de los datos para visualizar los datos',
                    'message' => $e->errors(),
                ], 422);
            }
            catch (\Exception $e) {
                return response()->json($e->getMessage());
            }
    }

    public function create(Request $request){

        try{

            $validator= $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['fecha', 'lugar','tipo','created_at','updated_at'])],
                'fecha'=>'required|date|date_format:d/m/Y',
                'lugar'=>'required|string|',
                'tipo'=>['required','string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Divizioni dei beni', 'Comunidad dei beni'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                }],
            ]);

            $formalizar=formalizar_Matrim12::create([
                'fecha'=>$validator['fecha'],
                'lugar'=>$validator['lugar'],
                'tipo'=>$validator['tipo'],
            ]);

            return response()->json($formalizar);

        }catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación al crear un registro en la tabla formalizar matrimonio',
                'message' => $e->errors(),
            ], 422);
        }catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function modificar(Request $request){

        try{

            $validator= $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['created_at','updated_at','id','fecha', 'lugar','tipo'])],
                'fecha'=>'required|date|date_format:d/m/Y',
                'lugar'=>'required|string|',
                'tipo'=>['required','string',
                function ($attribute, $value, $fail) {
                    $allowedValues = ['Divizioni dei beni', ' Comunidad dei beni'];
                    if (!in_array(strtolower($value), array_map('strtolower', $allowedValues))) {
                        $fail($attribute . ' Campo no valido');
                    }
                }],
            ]);

            $formalizar=formalizar_Matrim12::findOrFail($request->input('id'));

            $formalizar->update($validator);

            return response()->json($formalizar);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación al modificar un registro en la tabla formalizar matrimonio',
                'message' => $e->errors(),
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function destroy(Request $request){
        try{

            $validator = $request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'id' => 'required|numeric'
            ]);

           $formalizar=formalizar_Matrim12::findOrFail($validator['id']);
           $formalizar->delete();

           return response()->json($formalizar);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación con los datos para eliminar el registro de la tabla formalizar matrimonio',
                'message' => $e->errors(),
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
