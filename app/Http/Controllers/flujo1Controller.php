<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Flujo1;
use App\Models\Matrimonio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class flujo1Controller extends Controller
{

    public function getFlujo1()
    {
        try {
            $flujo = Flujo1::all();
            return response()->json($flujo);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function create(Request $request)
    {

        $llegadaController = app('App\Http\Controllers\LlegadaDocs11Controller');
        $formalizarController = app('App\Http\Controllers\FormalizarMatrimonio12Controller');
        $retirarController = app('App\Http\Controllers\RetirarDocs13Controller');
        $traduccionController = app('App\Http\Controllers\Traduccion14Controller');

        try {

            DB::beginTransaction();
          try{
            $validator = $request->validate([
                'id_matrimonio' => 'required|unique:flujo1s|numeric',
                'primer_Email' => 'nullable|date|date_format:d/m/Y',
                'email_Cubano' => 'nullable|date|date_format:d/m/Y',
                'coordinar_Matrim' => 'nullable|date|date_format:d/m/Y',
                'segundo_Email' => 'nullable|date|date_format:d/m/Y',
                'procura_minrex' => 'nullable|date|date_format:d/m/Y',
                'retirada_CM' => 'nullable|date|date_format:d/m/Y',
                'tercer_Email' => 'nullable|date|date_format:d/m/Y',
                'cm_minrex' => 'nullable|date|date_format:d/m/Y',
                'cuarto_Email' => 'nullable|date|date_format:d/m/Y',
            ]);


            if($request->Filled(['doc1','doc2','fecha_llegada'])){
                $datos=[
                    'doc1'=>$request->input('doc1'),
                    'doc2'=>$request->input('doc2'),
                    'fecha'=>$request->input('fecha_llegada')
                ];
                $requestD = new \Illuminate\Http\Request();
                $requestD->replace($datos);

                $llegada = $llegadaController->create($requestD);
                $llegada = json_encode($llegada->getData());
                $llegada = json_decode(($llegada));
                $data = $validator + ['id_llegada_documentos' => $llegada->id];
            }
            if($request->Filled(['tipo','lugar','fecha_formalizar','coordinar_Matrim'])){
                $datos=[
                    'tipo'=>$request->input('tipo'),
                    'lugar'=>$request->input('lugar'),
                    'fecha'=>$request->input('fecha_formalizar')
                ];
                $requestD = new \Illuminate\Http\Request();
                $requestD->replace($datos);

                $formalizar = $formalizarController->create($requestD);
                $formalizar = json_encode($formalizar->getData());
                $formalizar = json_decode(($formalizar));
                $data = $data + ['id_formalizarMatrimonio' => $formalizar->id];
            }
            if($request->Filled(['fecha_ProcuraRetirar','fecha_MatrimonioRetirar','cm_minrex'])){
                $datos=[
                    'fecha_Procura'=>$request->input('fecha_ProcuraRetirar'),
                    'fecha_Matrimonio'=>$request->input('fecha_MatrimonioRetirar'),
                ];
                $requestD = new \Illuminate\Http\Request();
                $requestD->replace($datos);

                $retirar = $retirarController->create($requestD);
                $retirar = json_encode($retirar->getData());
                $retirar = json_decode(($retirar));
                $data = $data + ['id_retiroDocsMinrex' => $retirar->id];
            }
            if($request->Filled(['fecha_ProcuraT','fecha_MatrimonioT','cuarto_Email'])){
                $datos=[
                    'fecha_Procura'=>$request->input('fecha_ProcuraT'),
                    'fecha_Matrimonio'=>$request->input('fecha_MatrimonioT'),
                ];
                $requestD = new \Illuminate\Http\Request();
                $requestD->replace($datos);

                $traduccion = $traduccionController->create($requestD);
                $traduccion = json_encode($traduccion->getData());
                $traduccion = json_decode(($traduccion));
                $data = $data + ['id_traduccion' => $traduccion->id];
            }

            $flujo1 = Flujo1::create($data);

            DB::commit();

            $matrimonio = Matrimonio::find($request->input('id_matrimonio'));
            $flujo1->llegadaDocs()->associate($llegada);
            if (isset($formalizar) && $formalizar) {
                $flujo1->formalizarMatrimonio()->associate($formalizar);
            }
            if (isset($retirar) && $retirar) {
                $flujo1->retiroDocs()->associate($retirar);
            }
            if (isset($traduccion) && $traduccion) {
                $flujo1->traduccion()->associate($traduccion);
            }
            $flujo1->matrimonio()->associate($matrimonio);

            return response()->json($flujo1);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        };
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function modificar(Request $request)
    {

        $llegadaController = app('App\Http\Controllers\LlegadaDocs11Controller');
        $formalizarController = app('App\Http\Controllers\FormalizarMatrimonio12Controller');
        $retirarController = app('App\Http\Controllers\RetirarDocs13Controller');
        $traduccionController = app('App\Http\Controllers\Traduccion14Controller');

        try {

            DB::beginTransaction();
          try{
            $validator = $request->validate([
                'id_matrimonio' => 'required|numeric',
                'primer_Email' => 'nullable|date|date_format:d/m/Y',
                'email_Cubano' => 'nullable|date|date_format:d/m/Y',
                'coordinar_Matrim' => 'nullable|date|date_format:d/m/Y',
                'segundo_Email' => 'nullable|date|date_format:d/m/Y',
                'procura_minrex' => 'nullable|date|date_format:d/m/Y',
                'retirada_CM' => 'nullable|date|date_format:d/m/Y',
                'tercer_Email' => 'nullable|date|date_format:d/m/Y',
                'cm_minrex' => 'nullable|date|date_format:d/m/Y',
                'cuarto_Email' => 'nullable|date|date_format:d/m/Y',
            ]);


            $flujo1=Flujo1::findOrFail($request->input('id_flujo'));

            if($request->Filled(['id_llegada','doc1','doc2','fecha_llegada'])){
                $datos=[
                    'id'=>$request->input('id_llegada'),
                    'doc1'=>$request->input('doc1'),
                    'doc2'=>$request->input('doc2'),
                    'fecha'=>$request->input('fecha_llegada')
                ];
                $requestD = new \Illuminate\Http\Request();
                $requestD->replace($datos);

                $llegada = $llegadaController->modificar($requestD);
                $llegada = json_encode($llegada->getData());
                $llegada = json_decode(($llegada));
                $data = $validator + ['id_llegada_documentos' => $llegada->id];
            }
            if($request->Filled(['id_formalizar','tipo','lugar','fecha_formalizar'])){
                $datos=[
                    'id'=>$request->input('id_formalizar'),
                    'tipo'=>$request->input('tipo'),
                    'lugar'=>$request->input('lugar'),
                    'fecha'=>$request->input('fecha_formalizar')
                ];
                $requestD = new \Illuminate\Http\Request();
                $requestD->replace($datos);

                $formalizar = $formalizarController->modificar($requestD);
                $formalizar = json_encode($formalizar->getData());
                $formalizar = json_decode(($formalizar));
                $data = $data + ['id_formalizarMatrimonio' => $formalizar->id];
            }
            if($request->Filled(['id_retirar','fecha_ProcuraRetirar','fecha_MatrimonioRetirar'])){
                $datos=[
                    'id'=>$request->input('id_retirar'),
                    'fecha_Procura'=>$request->input('fecha_ProcuraRetirar'),
                    'fecha_Matrimonio'=>$request->input('fecha_MatrimonioRetirar'),
                ];
                $requestD = new \Illuminate\Http\Request();
                $requestD->replace($datos);

                $retirar = $retirarController->modificar($requestD);
                $retirar = json_encode($retirar->getData());
                $retirar = json_decode(($retirar));
                $data = $data + ['id_retiroDocsMinrex' => $retirar->id];
            }
            if($request->Filled(['id_traduccion','fecha_ProcuraT','fecha_MatrimonioT'])){
                $datos=[
                    'id'=>$request->input('id_traduccion'),
                    'fecha_Procura'=>$request->input('fecha_ProcuraT'),
                    'fecha_Matrimonio'=>$request->input('fecha_MatrimonioT'),
                ];
                $requestD = new \Illuminate\Http\Request();
                $requestD->replace($datos);

                $traduccion = $traduccionController->modificar($requestD);
                $traduccion = json_encode($traduccion->getData());
                $traduccion = json_decode(($traduccion));
                $data = $data + ['id_traduccion' => $traduccion->id];
            }

            DB::commit();

            $flujo1->update($data);

            $matrimonio = Matrimonio::find($request->input('id_matrimonio'));
            $flujo1->matrimonio()->associate($matrimonio);

            return response()->json($flujo1);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        };
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function destroy (Request $request){
        try{
            $flujo=Flujo1::find($request->input('id'));
            $flujo->delete();
            $flujo->llegadaDocs()->delete();
            $flujo->formalizarMatrimonio()->delete();
            $flujo->retiroDocs()->delete();
            $flujo->traduccion()->delete();

            return response()->json($flujo);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
