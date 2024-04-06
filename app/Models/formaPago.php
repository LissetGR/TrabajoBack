<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class formaPago extends Model
{
    use HasFactory;

    protected $fillable=['id_matrimonio','tipo', 'fecha'];

    public function matrimonio(){
        return $this->hasOne(matrimonio::class, 'id_matrimonio');
    }
}
