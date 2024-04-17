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
        Schema::create('flujo1s', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('id_matrimonio');
            $table->unsignedBigInteger('id_llegada_documentos');
            $table->date('primer_Email')->nullable();
            $table->date('email_Cubano')->nullable();
            $table->date('coordinar_Matrim')->nullable();
            $table->unsignedBigInteger('id_formalizarMatrimonio')->nullable();
            $table->date('segundo_Email')->nullable();
            $table->date('procura_minrex')->nullable();
            $table->date('retirada_CM')->nullable();
            $table->date('tercer_Email')->nullable();
            $table->date('cm_minrex')->nullable();
            $table->unsignedBigInteger('id_retiroDocsMinrex')->nullable();
            $table->date('cuarto_Email')->nullable();
            $table->unsignedBigInteger('id_traduccion')->nullable();
            $table->string('observaciones')->nullable();


            $table->foreign('id_matrimonio')->references('numero')->on('matrimonios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_llegada_documentos')->references('id')->on('llegada__doc11s');
            $table->foreign('id_formalizarMatrimonio')->references('id')->on('formalizar__matrim12s');
            $table->foreign('id_retiroDocsMinrex')->references('id')->on('retirar__doc13s');
            $table->foreign('id_traduccion')->references('id')->on('traduccion14s');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flujo1s');
    }
};
