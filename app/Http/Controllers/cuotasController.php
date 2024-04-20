<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cuotas;
use App\Models\formaPago;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Rules\CamposPermitidos;

class cuotasController extends Controller
{
    public function getCuotas(Request $request){
        try{
            $validator=$request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'id'=>'required|numeric'
            ]);
            $cuotas= cuotas::where('id_formaPago',$validator['id'])->get();
            return response()->json($cuotas);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado',
            ], 404);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        }
        catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function getAllCuotas(Request $request){
        try{
            $cuotas= cuotas::all();
            return response()->json($cuotas);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }



    public function create(Request $request){
        try{
            $validator=$request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id_formaPago','cantidad','fecha'])],
               'id_formaPago'=>'required|numeric',
               'cantidad'=>'required|numeric',
               'fecha'=>'required|date'
            ]);

            $cuota=cuotas::create([
                'id_formaPago'=>$validator['id_formaPago'],
                'cantidad'=>$validator['cantidad'],
                'fecha'=>$validator['fecha']
            ]);

            $cuota->forma_pago->update([
               'monto_pago'=> $cuota->forma_pago->monto_pago+$cuota->cantidad,
            ]);

            return response()->json($cuota);

        }catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function modificar(Request $request){
        try{
            $validator=$request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id','id_formaPago','cantidad','fecha'])],
               'id_formaPago'=>'required|numeric',
               'cantidad'=>'required|numeric',
               'fecha'=>'required|date'
            ]);

            $cuota= cuotas::findOrFail($request->input('id'));
            $cantidadA=$cuota->cantidad;

            $cuota->update($validator);

            if($request->filled('cantidad')){
                $cuota->forma_pago->update([
                    'monto_pago'=> ($cuota->forma_pago->monto_pago-$cantidadA)+$request->input('cantidad'),
                 ]);
            }
            return response()->json($cuota);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado'+ $e->getMessage(),
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

    public function destroy(Request $request){
        try{
            $validator=$request->validate([
                '*' => ['sometimes', new CamposPermitidos(['id'])],
                'id'=>'required|numeric'
            ]);

           $cuota=cuotas::findOrFail($validator['id']);
           $cuota->delete();

           return response()->json($cuota);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => 'No se pudo encontrar el registro con el ID proporcionado'+ $e->getMessage(),
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
