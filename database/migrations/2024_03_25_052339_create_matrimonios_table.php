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
        Schema::create('matrimonios', function (Blueprint $table) {

            $table->integer('numero')->primary();
            $table->unsignedBigInteger('username_italiano');
            $table->unsignedBigInteger('username_cubano');
            $table->enum('tipo',['Per procura', 'Congiunto']);
            $table->enum('via_llegada',['Mail', 'Chiamata', 'Whatsapp','In busta']);
            $table->integer('costo');
            $table->date('fecha_llegada');

            $table->foreign('username_cubano')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('username_italiano')->references('id')->on('cliente_italianos')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrimonios');
    }
};
