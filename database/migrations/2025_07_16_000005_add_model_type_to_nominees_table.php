<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModelTypeToNomineesTable extends Migration
{
    public function up()
    {
        Schema::table('nominees', function (Blueprint $table) {
            $table->string('model_type')->nullable()->after('member_id');
        });
    }

    public function down()
    {
        Schema::table('nominees', function (Blueprint $table) {
            $table->dropColumn('model_type');
        });
    }
}
