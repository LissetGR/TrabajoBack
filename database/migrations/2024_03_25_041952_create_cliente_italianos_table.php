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
        Schema::create('cliente_italianos', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->primary();
            $table->string('email_registro');
            $table->foreign('id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

            // // $table->timestamp('created_at')->useCurrent();
            // // $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_italianos');
    }
};
