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
        Schema::create('coins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('symbol')->unique();
            $table->enum('type', ['coin', 'token', 'fiat', 'defi', 'nft'])->default('coin');
            $table->decimal('price_usd', 15, 4)->nullable();
            $table->decimal('price_brl', 15, 4)->nullable();
            $table->decimal('price_eur', 15, 4)->nullable();
            $table->string('chain_api')->nullable();
            $table->string('explorer_address')->nullable();
            $table->string('explorer_tx')->nullable();
            $table->string('explorer_token')->nullable();
            $table->string('volume_24h')->default(0);
            $table->string('volume_change_24h')->default(0);
            $table->string('percent_change_24h')->default(0);
            $table->boolean('show_wallet')->default(false);
            $table->double('decimals')->default(2);
            $table->unsignedInteger('token_based')->nullable();
            $table->foreign('token_based')->references('id')->on('coins')->nullOnDelete();
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
        Schema::dropIfExists('coins');
    }
};
