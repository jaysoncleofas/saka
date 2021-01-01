<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCottageImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cottage_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cottage_id');
            $table->foreign('cottage_id')->references('id')->on('cottages');
            $table->text('path');
            $table->boolean('is_cover')->default(0);
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
        Schema::dropIfExists('cottage_images');
    }
}
