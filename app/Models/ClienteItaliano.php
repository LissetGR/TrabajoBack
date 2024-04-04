<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteItaliano extends Cliente
{
    use HasFactory;

    protected $primaryKey= 'id';

    protected $fillable = ['id', 'email_registro'];
    public $incrementing = false;

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'id');
    }
}
