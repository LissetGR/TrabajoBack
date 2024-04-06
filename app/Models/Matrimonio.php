<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matrimonio extends Model
{
    use HasFactory;

    protected $primaryKey='numero';
    public $incrementing= false;
    protected $fillable=['numero', 'username_cubano','username_italiano','tipo','via_llegada','fecha_llegada', 'costo'];

    public function usuario_italiano(){
        return $this->hasOne(Cliente::class, 'username_italiano');
    }
    public function usuario_cubano(){
        return $this->hasOne(Cliente::class, 'username_cubano');
    }

    public function forma_pago(){
        return $this->belongsTo(formaPago::class);
    }

    public function flujo1(){
        return $this->belongsTo(flujo1::class);
    }

    public function flujo2(){
        return $this->belongsTo(flujo2::class);
    }

    public function flujo3(){
        return $this->belongsTo(flujo3::class);
    }

    public function observaciones(){
        return $this->belongsTo(observaciones::class);
    }
}
