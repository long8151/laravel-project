<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCheckoutClientInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_checkout_client_info', function (Blueprint $table) {
            $table->Increments('checkClientInfo_id');
            $table->string('client_id');
            $table->string('client_name');
            $table->string('email');
            $table->string('address');
            $table->string('phonenumber');
            $table->string('note');
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
        Schema::dropIfExists('tbl_checkout_client_info');
    }
}
