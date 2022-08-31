<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalMemoFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_memo_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('internal_memo_id')->nullable();
            $table->text('file', 65535)->nullable();
            $table->timestamps();

            $table->foreign('internal_memo_id')->references('id')->on('internal_memo')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        DB::STATEMENT("INSERT INTO internal_memo_files (internal_memo_id, file) SELECT id, file FROM internal_memo WHERE file IS NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_memo_files');
    }
}
