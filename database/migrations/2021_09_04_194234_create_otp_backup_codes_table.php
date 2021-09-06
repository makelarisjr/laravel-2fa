<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpBackupCodesTable extends Migration
{
    public function up()
    {
        Schema::create('otp_backup_codes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code')
                ->unique();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->timestamp('used_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('otp_backup_codes');
    }
}
