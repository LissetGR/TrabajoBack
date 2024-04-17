<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteItalianoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->cliente->id,
            'username'=>$this->cliente->username,
            'nombre_apellidos'=>$this->cliente->nombre_apellidos,
            'direccion'=>$this->cliente->direccion,
            'telefono'=>$this->cliente->telefono,
            'email'=>$this->cliente->email,
            'email_registro'=>$this->email_registro,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,

        ];
    }
}
