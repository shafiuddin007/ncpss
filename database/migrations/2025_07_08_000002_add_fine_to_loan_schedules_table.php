<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFineToLoanSchedulesTable extends Migration
{
    public function up()
    {
        Schema::table('loan_schedules', function (Blueprint $table) {
            $table->decimal('fine', 15, 2)->default(0)->after('due');
        });
    }

    public function down()
    {
        Schema::table('loan_schedules', function (Blueprint $table) {
            $table->dropColumn('fine');
        });
    }
}
