<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'nombre_apellidos',
        'direccion',
        'telefono',
        'email'
    ];

    public function matrimonio(){
        return $this->hasOne(Matrimonio::class, 'username_cubano', 'username');
    }

    public function cliente_italiano(){
        return $this->hasOne(ClienteItaliano::class ,'id', 'id');
    }

}
