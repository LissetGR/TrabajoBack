<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flujo3 extends Model
{
    use HasFactory;

    protected $fillable=['id_matrimonio','id_prepararDocs','cita_cubano','solicitud_visado','retiro_passport','ultimo_Email'];

    public function matrimonio(){
        return $this->belongsTo(matrimonio::class,'id_matrimonio');
    }

    public function preparacionDocumentos(){
        return $this->belongsTo(preparar_Docs31::class,'id_prepararDocs','id');
    }
}
