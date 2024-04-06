<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flujo2 extends Model
{
    use HasFactory;

    protected $fillable=['id_matrimonio','id_prepararDocs','cita_trans', 'quinto_Email','transc_embajada','fecha_solicVisa'];

    public function matrimonio(){
        return $this->belongsTo(matrimonio::class,'id_matrimonio');
    }

    public function preparacionDocumentos(){
        return $this->hasOne(preparar_Doc21::class,'id_prepararDocs');
    }
}
