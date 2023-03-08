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
        Schema::create('coins_network', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coin_id')->nullable();
            $table->unsignedInteger('blockchain_id')->nullable();
            $table->unsignedInteger('contract_type_id')->nullable();
            $table->string('contract')->nullable();
            $table->boolean('status')->default(true);
            $table->foreign('blockchain_id')->references('id')->on('coins')->nullOnDelete();
            $table->foreign('coin_id')->references('id')->on('coins')->nullOnDelete();
            $table->foreign('contract_type_id')->references('id')->on('coins_network_contract_type')->nullOnDelete();
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
        Schema::dropIfExists('coin_networks');
    }
};
