<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('email')->unique()->nullable();

            $table->string('fullname')->nullable();
            $table->string('nickname')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('video_link')->nullable();
            $table->text('description')->nullable();
            $table->text('profile_message')->nullable();

            // assumes 1 to 1 user type
            $table->integer('user_type');

            $table->integer('age')->nullable();
            $table->integer('zipcode')->nullable();
            $table->string('prefecture')->nullable();
            $table->string('municipality')->nullable();
            $table->string('gender')->nullable();
            $table->string('profession')->nullable();
            $table->string('job')->nullable();
            $table->string('image')->nullable();

            $table->string('status')->default('active'); // active, inactive

            $table->string('password');
            $table->rememberToken();

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
        Schema::dropIfExists('users');
    }
}
