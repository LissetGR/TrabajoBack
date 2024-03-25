<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flujo2s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('cita_trans');
            $table->date('quinto_Email');
            $table->boolean('transc_embajada');
            $table->date('sexto_Correo');
            $table->date('fecha_solicVisa');

            $table->unsignedBigInteger('id_matrimonio');
            $table->foreign('id_matrimonio')->references('numero')->on('matrimonios')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flujo2s');
    }
};
