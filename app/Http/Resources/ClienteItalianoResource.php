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
            'id' => $this->id,
            'username' => $this->username,
            'nombre_apellidos' => $this->nombre_apellidos,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'email_registro' => $this->email_registro,
            'cubano'=>$this->es_cubano,
            'numero_tramite' => $this->relationLoaded('matrimonio') && !is_null($this->matrimonio) ? $this->matrimonio->numero : ($this->relationLoaded('matrimonioItaliano') && !is_null($this->matrimonioItaliano) ? $this->matrimonioItaliano->numero : null),
            'observaciones' => $this->relationLoaded('matrimonio') && !is_null($this->matrimonio) ? $this->matrimonio->observaciones->descripcion : (($this->relationLoaded('matrimonioItaliano') && !is_null($this->matrimonioItaliano)) ? $this->matrimonioItaliano->observaciones->descripcion : null),
            'monto_pagar' => $this->relationLoaded('matrimonio') && !is_null($this->matrimonio) ? $this->matrimonio->costo : (($this->relationLoaded('matrimonioItaliano') && !is_null($this->matrimonioItaliano)) ? $this->matrimonioItaliano->costo : null),
            'monto_pago' => $this->relationLoaded('matrimonio') && !is_null($this->matrimonio) ? $this->matrimonio->forma_pago->monto_pago : (($this->relationLoaded('matrimonioItaliano') && !is_null($this->matrimonioItaliano)) ? $this->matrimonioItaliano->forma_pago->monto_pago : null),
            'tipo_tramite' => $this->determinarTipoTramite(),
            'fecha' => $this->relationLoaded('matrimonio') && !is_null($this->matrimonio) ? $this->matrimonio->fecha_llegada : (($this->relationLoaded('matrimonioItaliano') && !is_null($this->matrimonioItaliano)) ? $this->matrimonioItaliano->fecha_llegada : null),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }

    protected function determinarTipoTramite()
    {
        if ($this->relationLoaded('matrimonio') && !is_null($this->matrimonio) || ($this->relationLoaded('matrimonioItaliano') && !is_null($this->matrimonioItaliano))) {
            return 'matrimonio';
        }
    }
}
