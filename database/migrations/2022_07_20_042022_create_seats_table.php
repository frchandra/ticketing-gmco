<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('seats', function (Blueprint $table) {
            $table->id('seat_id');
            $table->string('name');
            $table->bigInteger('price');
            $table->string('link');
            $table->unsignedSmallInteger('attendStatus');
            $table->unsignedBigInteger('is_reserved'); //unix timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('seats');
    }
}
