<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryAdsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_ads_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('web_id')->nullable();
            $table->string('name');
            $table->integer('width');
            $table->integer('height');
            $table->text('description');
            $table->integer('title_char');
            $table->integer('text_char');
            $table->boolean('status')->default(1);
            $table->text('default')->nullable();
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
        Schema::dropIfExists('directory_ads_types');
    }
}
