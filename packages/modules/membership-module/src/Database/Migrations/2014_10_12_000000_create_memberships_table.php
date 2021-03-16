<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->string('referral_link')->unique();
            $table->string('referral_id');
            $table->integer('unilevel_points')->default(0);
            $table->integer('referral_points')->default(0);
            $table->string('transaction_hash')->unique();
            $table->string('admin_btc_wallet');
            $table->string('current_btc_value');
            $table->string('status')->default('pending');
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('denied_at')->nullable();
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
        Schema::dropIfExists('memberships');
    }
}
