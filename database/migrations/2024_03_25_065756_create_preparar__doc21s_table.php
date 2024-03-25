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
        Schema::create('preparar__doc21s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_flujo2');
            $table->date('doc_provItalia21');
            $table->boolean('solicitud_Trans');
            $table->boolean('delegacion');
            $table->boolean('certificado_residencia');
            $table->boolean('doc_idItaliano');


            $table->foreign('id_flujo2')->references('id')->on('flujo2s')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preparar__doc21s');
    }
};
