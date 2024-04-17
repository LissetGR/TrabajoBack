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
            $table->unsignedBigInteger('id_matrimonio');
            $table->unsignedBigInteger('id_prepararDocs');
            $table->date('cita_cubano')->nullable();
            $table->date('solicitud_visado')->nullable();
            $table->date('retiro_passport')->nullable();
            $table->date('ultimo_Email')->nullable();
            $table->string('observaciones')->nullable();


            $table->foreign('id_matrimonio')->references('numero')->on('matrimonios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_prepararDocs')->references('id')->on('preparar__docs31s');

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
