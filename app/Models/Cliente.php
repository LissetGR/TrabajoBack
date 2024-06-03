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
        'pasaporte',
        'nombre_apellidos',
        'direccion',
        'telefono',
        'email',
        'email_registro',
        'es_cubano'
    ];

    public function matrimonio() {
        return $this->hasOne(Matrimonio::class, 'username_cubano', 'id');
    }
    public function matrimonioItaliano() {
        return $this->hasOne(Matrimonio::class, 'username_italiano', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class ,'username', 'name');
    }

}
