<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDirectoryPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_directory_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("web_id")->nullable();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("order_item_id");
            $table->text("item")->nullable();
            $table->text("price")->nullable();
            $table->integer("listing_number")->default(0);
            $table->integer("current_number")->default(0);
            $table->string("status")->default("pending");
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
        Schema::dropIfExists('user_directory_packages');
    }
}
