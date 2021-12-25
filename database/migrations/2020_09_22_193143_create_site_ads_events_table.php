<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteAdsEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_ads_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('web_id')->nullable();
            $table->unsignedBigInteger('listing_id');
            $table->string('start_date');
            $table->string('end_date')->nullable();
            $table->foreign('listing_id')
                ->references('id')->on('site_ads_listings')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_ads_events');
    }
}
