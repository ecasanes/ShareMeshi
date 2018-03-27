<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodItemTimeslotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_item_timeslots', function (Blueprint $table) {
            $table->increments('id');

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->integer('food_item_id')->unsigned()->nullable();
            $table->foreign('food_item_id')
                ->references('id')
                ->on('food_items')
                ->onDelete('cascade');

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
        Schema::dropIfExists('food_item_timeslots');
    }
}
