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
        Schema::create('retirar__doc13s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_flujo1');
            $table->date('fecha_Procura');
            $table->date('fecha_Matrimonio');

            $table->foreign('id_flujo1')->references('id')->on('flujo1s')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retirar__doc13s');
    }
};
