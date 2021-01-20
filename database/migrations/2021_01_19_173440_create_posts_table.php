<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();//Relacion con la tabal usuarios

            $table->string('title');
            $table->string('slug')->unique();//Campo identificador unico
            $table->string('image')->nullable(); //nullable hace referencia a que el campo puede ser nulo
            $table->text('body');
            $table->text('iframe')->nullable();//campos embebidos
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');//Llave foranea que se grabara en la tabla
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
