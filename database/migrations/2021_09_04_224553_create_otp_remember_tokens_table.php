<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpRememberTokensTable extends Migration
{
    public function up()
    {
        Schema::create('otp_remember_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('token')
                ->unique();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('otp_remember_tokens');
    }
}