<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flujo1 extends Model
{
    use HasFactory;

    protected $fillable=['id_matrimonio','id_llegada_documentos','id_formalizarMatrimonio','id_retiroDocsMinrex','id_traduccion','primer_Email', 'email_Cubano','coordinar_Matrim','segundo_Email','procura_minrex','retirada_CM','tercer_Email','cm_minrex','cuarto_Email'];

    public function matrimonio(){
        return $this->belongsTo(matrimonio::class,'id_matrimonio');
    }
    public function llegadaDocs(){
        return $this->hasOne(llegada_Doc11::class,'id_llegada_documentos');
    }
    public function formalizarMatrimonio(){
        return $this->hasOne(formalizar_Matrim12::class,'id_formalizarMatrimonio');
    }
    public function retiroDocs(){
        return $this->hasOne(retirar_Doc13::class,'id_retiroDocsMinrex');
    }

    public function traduccion(){
        return $this->hasOne(traduccion14::class,'id_traduccion');
    }

}
