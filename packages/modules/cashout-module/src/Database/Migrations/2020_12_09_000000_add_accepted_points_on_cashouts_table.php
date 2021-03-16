<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcceptedPointsOnCashoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashouts', function (Blueprint $table) {
            $table->decimal('accepted_unilevel_points', 8, 2)->default(0)->after('unilevel_points');
            $table->decimal('accepted_referral_points', 8, 2)->default(0)->after('accepted_unilevel_points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cashouts', function (Blueprint $table) {
            $table->dropColumn('accepted_unilevel_points');
            $table->dropColumn('accepted_referral_points');
        });
    }
}
