<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('news_id')->nullable();
            $table->text('image', 65535)->nullable();
            $table->timestamps();

            $table->foreign('news_id')->references('id')->on('news')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        DB::STATEMENT("INSERT INTO news_images (news_id, image) SELECT id, image FROM news WHERE image IS NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_images');
    }
}
