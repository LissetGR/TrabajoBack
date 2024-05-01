<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Flujo1Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'id_matrimonio' =>$this->id_matrimonio,
            'id_llegada_documentos'=>$this->llegadaDocs,
            'primer_Email' =>$this->primer_Email,
            'email_Cubano' =>$this->email_Cubano,
            'coordinar_Matrim' =>$this->coordinar_Matrim,
            'id_formalizarMatrimonio'=>$this->formalizarMatrimonio,
            'segundo_Email' =>$this->segundo_Email,
            'procura_minrex' =>$this->procura_minrex,
            'retirada_CM' =>$this->retirada_CM,
            'tercer_Email' =>$this->tercer_Email,
            'cm_minrex' =>$this->cm_minrex,
            'id_retiroDocsMinrex'=>$this->retiroDocs,
            'cuarto_Email' =>$this->cuarto_Email,
            'id_traduccion'=>$this->traduccion,
            'observaciones'=>$this->observaciones,
        ];
    }
}
