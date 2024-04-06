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
            'username_italiano'=>$this->usuario_italiano->username,
            'username_cubano'=>$this->usuario_cubano->username,
            'fecha_llegada'=>$this->fecha_llegada,
            'costo'=>$this->costo,
            'tipo'=>$this->tipo,
            'via_llegada'=>$this->via_llegada,
        ];
    }
}
