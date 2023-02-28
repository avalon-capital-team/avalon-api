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
        Schema::create('data_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('iso2');
            $table->string('iso3');
        });

        Schema::table('users_address', function (Blueprint $table) {
            $table->unsignedInteger('country_id')->nullable()->after('state');
            $table->foreign('country_id')->references('id')->on('data_countries')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_countries');
        Schema::dropColumns('users_address', ['country_id']);
    }
};
