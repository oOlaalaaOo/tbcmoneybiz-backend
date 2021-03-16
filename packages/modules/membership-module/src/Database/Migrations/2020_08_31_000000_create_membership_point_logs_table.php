<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipPointLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_point_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('referred_user_id')->unsigned();
            $table->integer('referrer_user_id')->unsigned();
            $table->integer('points');
            $table->string('membership_type');
            $table->string('description');
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
        Schema::dropIfExists('membership_point_logs');
    }
}
