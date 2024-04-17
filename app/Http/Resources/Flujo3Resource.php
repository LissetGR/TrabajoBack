<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Flujo3Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'id_matrimonio' =>$this->id_matrimonio,
            'id_prepararDocs'=>$this->preparacionDocumentos,
            'cita_cubano' => $this->cita_cubano,
            'solicitud_visado' => $this->solicitud_visado,
            'retiro_passport' => $this->retiro_passport,
            'ultimo_Email' => $this->ultimo_Email,
            'observaciones'=>$this->observaciones,
        ];
    }
}
