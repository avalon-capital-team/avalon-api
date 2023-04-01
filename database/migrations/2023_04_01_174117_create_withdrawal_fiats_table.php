<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals_fiat', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coin_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('debit_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();
            $table->decimal('amount', 15, 6);
            $table->string('type');
            $table->json('data');
            $table->string('payment_confirmation')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->string('reject_motive')->nullable();
            $table->timestamps();
            $table->foreign('coin_id')->references('id')->on('coins')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('debit_id')->references('id')->on('credits')->nullOnDelete();
            $table->foreign('status_id')->references('id')->on('withdrawals_status')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawals_fiat');
    }
};
