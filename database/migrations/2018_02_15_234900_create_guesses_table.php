<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guesses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('round_id')->unsigned();
            $table->text('guess');
            $table->boolean('is_correct');
            $table->timestamps();

            $table->foreign('round_id')
                ->references('id')->on('rounds')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guesses');
    }
}
