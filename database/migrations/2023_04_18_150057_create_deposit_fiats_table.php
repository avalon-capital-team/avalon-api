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
        Schema::create('deposits_fiat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->unique();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('coin_id')->nullable();
            $table->unsignedInteger('payment_method_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->longText('message')->nullable();
            $table->string('receipt_file')->nullable();
            $table->string('provider_id')->nullable();
            $table->json('provider_data')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('coin_id')->references('id')->on('coins')->nullOnDelete();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->nullOnDelete();
            $table->foreign('status_id')->references('id')->on('deposits_status')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
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
        Schema::dropIfExists('deposits_fiat');
    }
};
