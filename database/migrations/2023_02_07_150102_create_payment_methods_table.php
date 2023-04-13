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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_show')->nullable();
            $table->string('type');
            $table->boolean('status')->default(false);
            $table->json('data')->nullable();
            $table->unsignedInteger('coin_id')->nullable();
            $table->foreign('coin_id')->references('id')->on('coins')->nullOnDelete();
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
        Schema::dropIfExists('payment_methods');
    }
};
