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
        Schema::create('credits', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('coin_id')->nullable();
            $table->decimal('amount', 15, 8);
            $table->string('description')->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();
            $table->boolean('external')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('type_id')->references('id')->on('credits_type')->nullOnDelete();
            $table->foreign('status_id')->references('id')->on('credits_status')->nullOnDelete();
            $table->foreign('coin_id')->references('id')->on('coins')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credits');
    }
};
