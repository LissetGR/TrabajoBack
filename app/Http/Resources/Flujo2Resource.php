<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Flujo2Resource extends JsonResource
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
            'id_prepararDocs' =>$this->preparacionDocumentos,
            'cita_trans' => $this->cita_trans,
            'quinto_Email' => $this->quinto_Email,
            'transc_embajada' => $this->transc_embajada,
            'sexto_Email' => $this->sexto_Email,
            'fecha_solicVisa' => $this->fecha_solicVisa,
            'observaciones' => $this->observaciones,
        ];
    }
}
