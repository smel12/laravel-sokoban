<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game-users', function (Blueprint $table) {
			$table->bigInteger('id')->unsigned() ;
			$table->primary('id') ;
			$table->string('email', 255)->unique() ;
			$table->string('language', 2) ;
			$table->integer('level') ;
			$table->integer('limit') ;
			$table->integer('moves') ;
			$table->string('control', 7) ;
			$table->string('state', 1000) ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game-users');
    }
}
