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
        Schema::create('coins_network_contract_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
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
        Schema::dropIfExists('coins_network_contract_type');
    }
};
