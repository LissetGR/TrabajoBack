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
        Schema::create('traduccion14s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_flujo1');
            $table->date('fechaProcura');
            $table->date('fechaMatrimonio');
            
            $table->foreign('id_flujo1')->references('id')->on('flujo1s')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traduccion14s');
    }
};
