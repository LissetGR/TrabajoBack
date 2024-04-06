<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class traduccion14 extends Model
{
    use HasFactory;

    protected $fillable=['fechaProcura', 'fechaMatrimonio'];

    public function Flujo1(){
        return $this->hasOne(Flujo1::class,'id_traduccion','id');
    }
}
