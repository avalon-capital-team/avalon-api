<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['user', 'mananger', 'advisor', 'admin'])->default('user');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('document_type', array('CPF', 'CNPJ'))->nullable();
            $table->string('document')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('sponsor_id')->nullable();
            $table->unsignedInteger('status_id')->nullable()->default(1);
            $table->date('birth_date')->nullable();
            $table->string('password');
            $table->integer('verification_code')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('sponsor_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
