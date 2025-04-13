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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('gender');
            $table->string('nid');
            $table->date('dob');
            $table->integer('nationality');
            $table->integer('religion');
            $table->string('place_of_birth');
            $table->string('marital_status');       
            $table->string('occupation')->nullable();
            $table->string('blood_group');
            $table->string('photo');
            $table->string('signature');       
            $table->string('guardian_occuption')->nullable(); 
            $table->string('educational_level')->nullable();
            $table->string('mobile');   
            $table->string('email')->nullable();

            $table->string('pre_address')->nullable;   
            $table->integer('pre_division')->nullable();
            $table->integer('pre_district')->nullable;
            $table->integer('pre_thana')->nullable;
            $table->integer('pre_union')->nullable;
            $table->string('pre_post_code')->nullable;
            
            $table->string('per_address')->nullable;
            $table->integer('per_division')->nullable;
            $table->integer('per_district')->nullable;
            $table->integer('per_thana')->nullable;
            $table->integer('per_union')->nullable;
            $table->string('per_post_code')->nullable;
           
            
            $table->string('status')->default('pending');
            $table->string('created_by');
            $table->string('updated_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
