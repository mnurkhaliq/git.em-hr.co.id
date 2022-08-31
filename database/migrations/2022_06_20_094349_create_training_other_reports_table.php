<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingOtherReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_other_report', function (Blueprint $table) {
            $table->id();
            $table->integer('training_id');
            $table->integer('training_other_id');
            $table->integer('level_id');
			$table->integer('approval_id')->nullable();
            $table->integer('approved')->nullable();
			$table->text('note', 65535)->nullable();
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
        Schema::dropIfExists('training_other_report');
    }
}
