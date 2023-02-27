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
        Schema::create('users_compliance', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();
            $table->enum('type', array('manual', 'kycaid'))->default('manual');
            $table->string('applicant_id')->nullable();
            $table->string('form_id')->nullable();
            $table->string('form_url')->nullable();
            $table->string('verification_id')->nullable();
            $table->json('last_callback')->nullable();
            $table->json('documents')->nullable();
            $table->string('message')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreign('status_id')->references('id')->on('users_compliance_status')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_compliance');
    }
};
