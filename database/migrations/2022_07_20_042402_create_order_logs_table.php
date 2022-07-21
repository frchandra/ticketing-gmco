<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('order_logs', function (Blueprint $table) {
            $table->id('orderLog_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seat_id');
            $table->string('buyer_email');
            $table->string('seat_name');
            $table->unsignedBigInteger('price');
            $table->string('tf_proof');
            $table->timestamps();

            $table->foreign('buyer_id')->references('buyer_id')->on('buyers');
            $table->foreign('seat_id')->references('seat_id')->on('seats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('order_logs');
    }
}
