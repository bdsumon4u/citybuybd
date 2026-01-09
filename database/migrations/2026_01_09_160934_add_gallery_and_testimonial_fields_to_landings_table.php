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
        Schema::table('landings', function (Blueprint $table) {
            $table->string('gallery_title')->nullable()->after('slider_title');
            $table->string('testimonial_title')->nullable()->after('gallery_title');
            $table->text('testimonials')->nullable()->after('testimonial_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->dropColumn(['gallery_title', 'testimonial_title', 'testimonials']);
        });
    }
};
