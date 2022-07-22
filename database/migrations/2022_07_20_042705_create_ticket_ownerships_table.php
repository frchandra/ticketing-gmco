<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketOwnershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('ticket_ownerships', function (Blueprint $table) {
            $table->id('ticketOwnership_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seat_id');
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
        Schema::dropIfExists('ticket_ownerships');
    }
}
