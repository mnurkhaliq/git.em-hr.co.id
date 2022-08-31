<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashAdvanceFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_advance_form', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_advance_id')->nullable();
			$table->text('description', 65535)->nullable();
			$table->integer('quantity')->nullable();
			$table->integer('estimation_cost')->nullable();
			$table->integer('amount')->nullable();
			$table->text('note', 65535)->nullable();
			$table->integer('nominal_approved')->nullable();
            $table->integer('nominal_claimed')->nullable();
            $table->text('note_claimed', 65535)->nullable();
			$table->text('file_struk', 65535)->nullable();
			$table->string('type_form', 25);
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
        Schema::dropIfExists('cash_advance_form');
    }
}
