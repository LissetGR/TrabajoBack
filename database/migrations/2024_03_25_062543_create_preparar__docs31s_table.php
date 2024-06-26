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
        Schema::create('preparar__docs31s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('doc_provItalia31');
            $table->boolean('declaracion_alojamiento');
            $table->boolean('reserva_aerea');
            $table->boolean('certificado_residenciaItaliano');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preparar__docs31s');
    }
};
