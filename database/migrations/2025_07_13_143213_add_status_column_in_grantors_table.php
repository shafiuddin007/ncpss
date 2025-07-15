<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Status;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('grantors', function (Blueprint $table) {
            //
            $table->foreignId('applicant_id')->after('loan_id');
            $table->enum('status', array_column(Status::cases(), 'value'))->default(Status::APPROVED->value)->after('loan_amount');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grantors', function (Blueprint $table) {
            //
            $table->dropColumn('status');
            $table->dropColumn('applicant_id');
        });
    }
};


