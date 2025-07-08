<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentByAndDueToLoanSchedulesTable extends Migration
{
    public function up()
    {
        Schema::table('loan_schedules', function (Blueprint $table) {
            $table->string('payment_by')->nullable()->after('paid_date');
            $table->decimal('due', 15, 2)->default(0)->after('total_payment');
        });
    }

    public function down()
    {
        Schema::table('loan_schedules', function (Blueprint $table) {
            $table->dropColumn(['payment_by', 'due']);
        });
    }
}
