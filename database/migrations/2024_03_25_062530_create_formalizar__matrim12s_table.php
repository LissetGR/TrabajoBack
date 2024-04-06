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
        Schema::create('formalizar__matrim12s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha');
            $table->string('lugar');
            $table->enum('tipo',['Divizioni dei beni', ' Comunidad dei beni']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formalizar__matrim12s');
    }
};
