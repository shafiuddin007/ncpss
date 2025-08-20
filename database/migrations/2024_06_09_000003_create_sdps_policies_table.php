<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdps_policies', function (Blueprint $table) {
            $table->id();
            $table->decimal('monthly_installment', 16, 2)->default(0);
            $table->integer('maturity_period')->default(0);
            $table->decimal('after_machurity_pay', 16, 2)->default(0);
            $table->decimal('bonus', 16, 2)->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdps_policies');
    }
};
