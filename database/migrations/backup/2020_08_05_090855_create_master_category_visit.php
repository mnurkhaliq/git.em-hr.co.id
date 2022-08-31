<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterCategoryVisit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_category_visit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('master_category_name')->nullable();
        });
        DB::table('master_category_visit')->insert(
            [
                [
                    'id' => 1,
                    'master_category_name' => 'Sales / Marketing'
                ],
                [
                    'id' => 2,
                    'master_category_name' => 'Medical'
                ],
                [
                    'id' => 3,
                    'master_category_name' => 'Telecomunication / Information Technology'
                ],
                [
                    'id' => 4,
                    'master_category_name' => 'Engineering'
                ],
                [
                    'id' => 5,
                    'master_category_name' => 'Finance / Banking / Insurance'
                ],
                [
                    'id' => 6,
                    'master_category_name' => 'Logistic'
                ]
            ]
          );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_category_visit');
    }
}
