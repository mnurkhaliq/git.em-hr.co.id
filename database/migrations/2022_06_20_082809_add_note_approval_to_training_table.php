<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteApprovalToTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_daily', function (Blueprint $table) {
            $table->text('note_approval', 65535)->nullable();
        });

        Schema::table('training_allowance', function (Blueprint $table) {
            $table->text('note_approval', 65535)->nullable();
        });

        Schema::table('training_other', function (Blueprint $table) {
            $table->text('note_approval', 65535)->nullable();
        });

        Schema::table('training_transportation', function (Blueprint $table) {
            $table->text('note_approval', 65535)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training', function (Blueprint $table) {
            //
        });
    }
}
