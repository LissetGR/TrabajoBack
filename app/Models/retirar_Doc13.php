<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class retirar_Doc13 extends Model
{
    use HasFactory;

    protected $fillable=['fecha_Procura', 'fecha_Matrimonio'];

    public function Flujo1(){
        return $this->belongsTo(Flujo1::class);
    }
}
