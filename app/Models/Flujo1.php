<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flujo1 extends Model
{
    use HasFactory;

    protected $fillable=['id_matrimonio','id_llegada_documentos','id_formalizarMatrimonio','id_retiroDocsMinrex','id_traduccion','primer_Email', 'email_Cubano','coordinar_Matrim','segundo_Email','procura_minrex','retirada_CM','tercer_Email','cm_minrex','cuarto_Email'];

    protected $hidden = ['llegadaDocs','matrimonio'];

    public function matrimonio(){
        return $this->belongsTo(matrimonio::class,'id_matrimonio', 'numero');
    }
    public function llegadaDocs(){
        return $this->belongsTo(llegada_Doc11::class,'id_llegada_documentos','id');
    }
    public function formalizarMatrimonio(){
        return $this->belongsTo(formalizar_Matrim12::class,'id_formalizarMatrimonio','id');
    }
    public function retiroDocs(){
        return $this->belongsTo(retirar_Doc13::class,'id_retiroDocsMinrex','id');
    }

    public function traduccion(){
        return $this->belongsTo(traduccion14::class,'id_traduccion','id');
    }

}
