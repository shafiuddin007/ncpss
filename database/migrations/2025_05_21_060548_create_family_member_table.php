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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('loan_id'); 
            $table->unsignedBigInteger('member_id');
           
            $table->string('relation')->nullable();
           
            $table->decimal('current_deposit')->default(0);
            $table->decimal('current_loan')->default(0);
            
            // Signature/document storage
            $table->string('signature_path')->nullable();
            $table->timestamps();
           
            // Foreign key constraints
            $table->foreign('loan_id')
                  ->references('id')
                  ->on('loans')
                  ->onDelete('cascade');
                  
            $table->foreign('member_id')
                  ->references('id')
                  ->on('members')
                  ->onDelete('restrict');
            
            // Indexes for performance
            $table->index(['loan_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};