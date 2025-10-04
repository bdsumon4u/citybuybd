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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_two')->nullable();
            $table->string('phone_three')->nullable();
            $table->string('email')->nullable();
            $table->string('email_two')->nullable();
            $table->string('fb_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('yt_link')->nullable();
            $table->string('insta_link')->nullable();
            $table->text('copyright')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('currency')->nullable();
            $table->string('bkash')->nullable();
            $table->text('fb_pixel')->nullable();
            $table->text('about_us')->nullable();
            $table->text('delivery_policy')->nullable();
            $table->text('return_policy')->nullable();
            $table->text('google_sheet')->nullable();
            
          
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
        Schema::dropIfExists('settings');
    }
};
