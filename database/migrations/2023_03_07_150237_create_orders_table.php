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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->unique();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('payment_method_id')->nullable();
            $table->unsignedInteger('coin_id')->nullable();
            $table->unsignedInteger('plan_id')->nullable();
            $table->decimal('total', 15, 4);
            $table->decimal('fee', 15, 4)->default(0);
            $table->unsignedInteger('status_id')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->string('approve_type')->nullable();
            $table->unsignedInteger('manual_by')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('coin_id')->references('id')->on('coins')->nullOnDelete();
            $table->foreign('plan_id')->references('id')->on('data_plans')->nullOnDelete();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->nullOnDelete();
            $table->foreign('status_id')->references('id')->on('orders_status')->nullOnDelete();
            $table->foreign('manual_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('credits', function ($table) {
            $table->unsignedInteger('order_id')->nullable()->after('status_id');
            $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');

        Schema::table('credits', function ($table) {
            $table->dropColumn('order_id');
        });
    }
};
