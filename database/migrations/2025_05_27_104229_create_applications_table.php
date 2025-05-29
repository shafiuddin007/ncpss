<?php

use App\Enums\ApprovalStatus;
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
    Schema::create('applications', function (Blueprint $table) {
        $table->id();
        
        // Polymorphic relationship
        $table->unsignedBigInteger('model_id');
        $table->string('model_type');
        
        $table->string('application_number')->unique();
        $table->unsignedTinyInteger('approval_step')->default(1);
        $table->string('status')->default(ApprovalStatus::PENDING->value);
        $table->text('notes')->nullable();
        
        // Status tracking
        $table->boolean('is_approved')->default(false);
        $table->boolean('is_rejected')->default(false);
        
        // Date fields
        $table->date('application_date')->useCurrent();
        $table->date('approval_date')->nullable();
        $table->date('rejection_date')->nullable();
        
        // User references with name storage
        $table->unsignedBigInteger('approved_by')->nullable();
        $table->string('approved_by_name')->nullable();
        $table->unsignedBigInteger('rejected_by')->nullable();
        $table->string('rejected_by_name')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();
        $table->string('created_by_name')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->string('updated_by_name')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();
        $table->string('deleted_by_name')->nullable();
        
        // Soft delete tracking
        $table->boolean('is_deleted')->default(false);
        $table->softDeletes();
        $table->timestamps();

        // Indexes
        $table->index(['model_id', 'model_type']);
        $table->index('application_number');
        $table->index('status');
        $table->index('application_date');

        // Foreign keys
        $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
