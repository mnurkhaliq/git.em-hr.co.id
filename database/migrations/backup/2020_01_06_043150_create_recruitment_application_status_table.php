<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentApplicationStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_application_status', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');

            $table->primary('id');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
        DB::table('recruitment_application_status')->insert(
          [
              [
                  'id' => 0,
                  'status' => 'Waiting'
              ],
              [
                  'id' => 1,
                  'status' => 'Approved'
              ],
              [
                  'id' => 2,
                  'status' => 'Shortlisted'
              ],
              [
                  'id' => 3,
                  'status' => 'Rejected'
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
        Schema::dropIfExists('recruitment_application_status');
    }
}
