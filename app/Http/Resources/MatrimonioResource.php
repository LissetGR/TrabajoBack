<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatrimonioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'numero'=>$this->numero,
            'username_italiano'=> $this->usuario_italiano->nombre_apellidos,
            'username_cubano'=>$this->usuario_cubano->nombre_apellidos,
            'fecha_llegada'=>$this->fecha_llegada,
            'costo'=>$this->costo,
            'tipo'=>$this->tipo,
            'via_llegada'=>$this->via_llegada,
            'forma_pago'=>$this->forma_pago,
            'observaciones'=>$this->observaciones,
            'flujo1'=>new Flujo1Resource($this->flujo1),
            'flujo2'=>new Flujo2Resource($this->flujo2),
            'flujo3'=>new Flujo3Resource($this->flujo3),
        ];
    }
}
