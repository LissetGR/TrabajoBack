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

            $table->date('primer_Email');
            $table->date('email_Cubano');
            $table->boolean('coordinar_Matrim');
            $table->date('segundo_Email');
            $table->boolean('procura_minrex');
            $table->date('retirada_CM');
            $table->date('tercer_Email');
            $table->date('cm_minrex');
            $table->date('cuarto_Email');

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
        Schema::dropIfExists('flujo1s');
    }
};
