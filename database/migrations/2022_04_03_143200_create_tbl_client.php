<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_client', function (Blueprint $table) {
            $table->increments('client_id');
            $table->string('client_email');
            $table->string('password');
            $table->string('client_fullname');
            $table->string('client_phonenumber');
            $table->string('client_address');
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
        Schema::dropIfExists('tbl_client');
    }
}
