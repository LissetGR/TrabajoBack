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
            $table->string('username_italiano');
            $table->string('username_cubano');
            $table->enum('tipo',['PER PROCURA', 'CONGIUNTO']);
            $table->enum('via_llegada',['Mail', 'Chiamata', 'Whatsapp','In busta']);
            $table->integer('costo');
            $table->date('fecha_llegada');


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
