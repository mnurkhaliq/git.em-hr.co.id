<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterVisitType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_visit_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('master_visit_type_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
            
        });
        DB::table('master_visit_type')->insert(
            [
                [
                    'id' => 1,
                    'master_visit_type_name' => 'Lock',
                    'description' => 'lock = features used for visits that have been determined based on the point of location,but can be used for free visits by using feature "out of branch / point"' 
                ],
                [
                    'id' => 2,
                    'master_visit_type_name' => 'Unlock',
                    'description' => 'features used for free visits or the point is not determined' 
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
        Schema::dropIfExists('master_visit_type');
    }
}
