<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grantors', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('grantor_member_id');
            
            $table->decimal('deposit_amount')->default(0);
            $table->decimal('loan_amount')->default(0);
            
            $table->string('document_path')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('loan_id')
                  ->references('id')
                  ->on('loans')
                  ->onDelete('cascade');
                  
            $table->foreign('grantor_member_id')
                  ->references('id')
                  ->on('members')
                  ->onDelete('restrict');
            
            $table->index(['loan_id', 'grantor_member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grantors');
    }
};