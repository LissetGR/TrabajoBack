<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class reciboResource extends JsonResource
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
            'costo'=>$this->costo,
            'monto_pago'=>$this->forma_pago->monto_pago,
            'fecha_pago'=>$this->forma_pago->fecha,
            'cuotas'=>$this->forma_pago->cuotas
        ];
    }
}
