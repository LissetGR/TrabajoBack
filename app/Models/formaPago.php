<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class formaPago extends Model
{
    use HasFactory;


    protected $fillable=['id_matrimonio','tipo', 'fecha', 'monto_pago'];

    public function matrimonio(){
        return $this->belongsTo(matrimonio::class, 'id_matrimonio', 'numero');
    }

    public function cuotas(){
        return $this->hasMany(cuotas::class, 'id_formaPago', 'id');
    }
}
