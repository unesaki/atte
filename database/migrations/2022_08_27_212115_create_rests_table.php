<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rests', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->nullable(false)->autoIncrement();
            $table->bigInteger('user_id')->nullable(false)->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->datetime('restIn')->nullable();
            $table->datetime('restOut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rests');
    }
}
