<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perguntas', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedTinyInteger('numero');
            $table->text('texto');
            $table->unsignedTinyInteger('tipo')->comment('1: S/N; 2: S/AV/N/NS; 3: 1-5; 4: texto');
            $table->boolean('feedback')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perguntas');
    }
}
