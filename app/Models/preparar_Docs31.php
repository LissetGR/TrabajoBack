<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class preparar_Docs31 extends Model
{
    use HasFactory;

    protected $fillable=['doc_provItalia31','declaracion_alojamiento','reserva_aerea','certificado_residenciaItaliano'];

    public function Flujo3(){
        return $this->HasOne(Flujo3::class,'id_prepararDocs','id');
    }
}
