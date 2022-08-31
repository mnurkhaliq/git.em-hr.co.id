<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAttachmentToTrainingTransportationTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_transportation_type', function (Blueprint $table) {
            $table->tinyInteger('is_attachment')->default(1)->after('name');
        });

        \DB::STATEMENT("INSERT INTO training_transportation_type(name, is_attachment) VALUES ('Hotel In Lieu', 0)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_transportation_type', function (Blueprint $table) {
            //
        });
    }
}
