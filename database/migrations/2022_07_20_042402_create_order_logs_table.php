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
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seat_id');
            $table->string('buyer_email');
            $table->string('buyer_phone');
            $table->string('buyer_fname');
            $table->string('seat_name');
            $table->unsignedBigInteger('price');
            $table->string('vendor');
            $table->string('confirmation');
            $table->unsignedSmallInteger('case'); // 0=normal; mennyelesaikan transaksi tepatwaktu,
                                                        // 1=to_late; menyelesaikan transaksi telat-> terlanjur transfer dan gak dapet kursi
                                                        // 2=luck; menyelesaikan transaksi telat -> terlanjur tf tapi kursi masih kosong
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
