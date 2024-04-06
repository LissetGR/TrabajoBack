<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class observaciones extends Model
{
    use HasFactory;

    protected $fillable=['descripcion','id_matrimonio'];

    public function matrimonio(){
        return $this->belongsTo(matrimonio::class,'id_matrimonio', 'numero');
    }
}
