<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ApprovalStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id();

            // Application relationship
            $table->foreignId('application_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('approval_step')->default(1);
            $table->string('approval_role')->nullable();
            $table->string('status')->default(ApprovalStatus::PENDING->value);
            $table->string('remarks')->nullable();
            $table->string('document_path')->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('approved_by_name')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('application_id');
            $table->index(['application_id', 'approval_step']);
            $table->index('status');
            $table->index('approval_date');
            $table->index('approved_by');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_histories');
    }
};
