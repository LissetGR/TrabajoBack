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
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('pasaporte');
            $table->string('nombre_apellidos');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');

            $table->foreign('username')->references('name')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

            // $table->timestamp('created_at')->useCurrent();
            // $table->timestamp('updated_at')->useCurrent();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
