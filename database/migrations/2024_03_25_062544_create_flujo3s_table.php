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
        Schema::create('flujo3s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('cita_cubano');
            $table->date('solicitud_visado');
            $table->boolean('retiro_passport');
            $table->date('ultimo_Email');

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
        Schema::dropIfExists('flujo3s');
    }
};
