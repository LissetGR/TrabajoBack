<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class preparar_Doc21 extends Model
{
    use HasFactory;

    protected $fillable=['doc_provItalia21','solicitud_Trans','delegacion','certificado_residencia','doc_idItaliano'];

    // public function Flujo2(){
    //     return $this->belongsTo(Flujo2::class);
    // }
}
