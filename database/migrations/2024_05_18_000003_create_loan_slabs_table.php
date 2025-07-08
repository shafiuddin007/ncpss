<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanSlabsTable extends Migration
{
    public function up()
    {
        Schema::create('loan_slabs', function (Blueprint $table) {
            $table->id();
            $table->string('loan_serial');
            $table->decimal('maximum_loan_receivable', 15, 2);
            $table->integer('times');
            $table->integer('number_of_installment');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loan_slabs');
    }
}
