<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cuotas extends Model
{
    use HasFactory;

    protected $fillable=['id_formaPago', 'cantidad', 'fecha'];
    
    protected $hidden = ['forma_pago'];

    public function forma_pago(){
        return $this->belongsTo(formaPago::class, 'id_formaPago','id');
    }
}
