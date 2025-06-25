<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('blood_group')->nullable()->change();
            $table->string('nid')->nullable()->change();
            $table->string('educational_level')->nullable()->change();
            
        });

        Schema::table('nominees', function (Blueprint $table) {
            $table->string('nid_birth_no')->nullable()->change();
            $table->integer('age')->nullable()->change();
            $table->string('contact_no')->nullable()->change();
            $table->string('address')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('blood_group')->nullable(false)->change();
            $table->string('nid')->nullable(false)->change();
            $table->string('educational_level')->nullable(false)->change();
            
        });

        Schema::table('nominees', function (Blueprint $table) {
            $table->string('nid_birth_no')->nullable(false)->change();
            $table->integer('age')->nullable(false)->change();
            $table->string('contact_no')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
        });
    }
};
