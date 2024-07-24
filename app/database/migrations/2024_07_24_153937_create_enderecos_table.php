<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idPessoa');
            $table->string('tipo');
            $table->string('cep');
            $table->string('logradouro')->nullable();
            $table->string('numero', 10);
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cidade');
            $table->timestamps();

            $table->foreign('idPessoa')->references('id')->on('pessoas')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enderecos');
    }
}
