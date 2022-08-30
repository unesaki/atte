<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->nullable(false)->autoIncrement();
            $table->bigInteger('user_id')->nullable(false)->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('rest_id')->nullable()->unsigned();
            $table->foreign('rest_id')->references('id')->on('rests');
            $table->datetime('punchIn');
            $table->datetime('punchOut')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}
