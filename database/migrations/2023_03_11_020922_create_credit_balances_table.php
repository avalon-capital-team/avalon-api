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
        Schema::create('credits_balance', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('coin_id')->nullable();
            $table->decimal('balance_enable', 15, 6)->default(0);
            $table->decimal('balance_pending', 15, 6)->default(0);
            $table->decimal('balance_canceled', 15, 6)->default(0);
            $table->decimal('sales', 15, 6)->default(0);
            $table->decimal('deposited', 15, 6)->default(0);
            $table->decimal('used', 15, 6)->default(0);
            $table->decimal('withdrawal', 15, 6)->default(0);
            $table->decimal('received', 15, 6)->default(0);
            $table->unsignedInteger('plan_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('coin_id')->references('id')->on('coins')->nullOnDelete();
            $table->foreign('plan_id')->references('id')->on('data_plans')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credits_balance');
    }
};
