<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class formalizar_Matrim12 extends Model
{
    use HasFactory;

    protected $fillable= ['fecha', 'lugar', 'tipo'];

    public function Flujo1(){
        return $this->belongsTo(Flujo1::class);
    }
}
