<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flujo3 extends Model
{
    use HasFactory;

    protected $fillable=['id_matrimonio','id_prepararDocs','cita_cubano','solicitud_visado','retiro_passport','ultimo_Email'];

    public function preparacionDocumentos(){
        return $this->hasOne(preparar_Docs31::class,'id_prepararDocs');
    }
}
