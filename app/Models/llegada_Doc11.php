<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class llegada_Doc11 extends Model
{
    use HasFactory;

    protected $fillable=['fecha', 'doc1', 'doc2'];

    public function Flujo1(){
        return $this->belongsTo(Flujo1::class);
    }

}
