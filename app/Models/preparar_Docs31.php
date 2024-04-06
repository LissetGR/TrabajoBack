<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class preparar_Docs31 extends Model
{
    use HasFactory;

    protected $fillable=['doc_provItalia31','declaracion_alojamiento','reserva_aerea','certificado_residenciaItaliano'];

    public function Flujo3(){
        return $this->belongsTo(Flujo3::class);
    }
}
