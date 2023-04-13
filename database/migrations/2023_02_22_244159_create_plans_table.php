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
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->unique();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('user_plan_id')->nullable();
            $table->unsignedInteger('plan_id')->nullable();
            $table->unsignedInteger('coin_id')->nullable();
            $table->unsignedInteger('payment_method_id')->nullable();
            $table->decimal('amount', 15, 8);
            $table->decimal('income', 15, 8);
            $table->boolean('acting')->default(false);
            $table->boolean('withdrawal_report')->default(false);
            $table->string('payment_voucher_url')->nullable();
            $table->dateTime('activated_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('plan_id')->references('id')->on('data_plans')->nullOnDelete();
            $table->foreign('user_plan_id')->references('id')->on('users_plan')->nullOnDelete();
            $table->foreign('coin_id')->references('id')->on('coins')->nullOnDelete();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->nullOnDelete();
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
        Schema::dropIfExists('plans');
    }
};
