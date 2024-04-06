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
            $table->unsignedBigInteger('id_matrimonio');
            $table->unsignedBigInteger('id_prepararDocs');
            $table->date('cita_trans');
            $table->date('quinto_Email')->nullable();
            $table->date('transc_embajada')->nullable();
            $table->date('sexto_Email')->nullable();
            $table->date('fecha_solicVisa')->nullable();


            $table->foreign('id_matrimonio')->references('numero')->on('matrimonios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_prepararDocs')->references('id')->on('preparar__doc21s');

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
