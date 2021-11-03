<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('otp_devices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('type');
            $table->string('otp_secret');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->index(['model_id', 'model_type']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('otp_devices');
    }
}
