<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_phases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('recruitment_type_id');
            $table->string('name');
            $table->tinyInteger('order');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->foreign('recruitment_type_id')->references('id')->on('recruitment_type')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::table('recruitment_phases')->insert(
            [
                [
                    'name' => 'SCREENING',
                    'order' => 1,
                    'recruitment_type_id' => 1,
                ],
                [
                    'name' => 'TECHNICAL EXAM',
                    'order' => 2,
                    'recruitment_type_id' => 1,
                ],
                [
                    'name' => 'INTERVIEW HR & USER',
                    'order' => 3,
                    'recruitment_type_id' => 1,
                ],
                [
                    'name' => 'TRANSFER/PROMOTION',
                    'order' => 4,
                    'recruitment_type_id' => 1,
                ],
            ]
        );
        DB::table('recruitment_phases')->insert(
            [
                [
                    'name' => 'SCREENING',
                    'order' => 1,
                    'recruitment_type_id' => 2,
                ],
                [
                    'name' => 'PSYCHOTEST',
                    'order' => 2,
                    'recruitment_type_id' => 2,
                ],
                [
                    'name' => 'TECHNICAL EXAM',
                    'order' => 3,
                    'recruitment_type_id' => 2,
                ],
                [
                    'name' => 'INTERVIEW HR & USER',
                    'order' => 4,
                    'recruitment_type_id' => 2,
                ],
                [
                    'name' => 'REFERENCE CHECK',
                    'order' => 5,
                    'recruitment_type_id' => 2,
                ],
                [
                    'name' => 'MEDICAL CHECK UP',
                    'order' => 6,
                    'recruitment_type_id' => 2,
                ],
                [
                    'name' => 'JOB OFFER',
                    'order' => 7,
                    'recruitment_type_id' => 2,
                ],
                [
                    'name' => 'HIRING',
                    'order' => 8,
                    'recruitment_type_id' => 2,
                ],
                [
                    'name' => 'ONBOARDING',
                    'order' => 9,
                    'recruitment_type_id' => 2,
                ],
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
        Schema::dropIfExists('recruitment_phases');
    }
}
