<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BatchJobNameInRecruitmentRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::STATEMENT('UPDATE recruitment_request rr INNER JOIN structure_organization_custom so ON rr.structure_organization_custom_id = so.id LEFT JOIN organisasi_position op ON so.organisasi_position_id = op.id LEFT JOIN organisasi_division od ON so.organisasi_division_id = od.id SET job_position = CONCAT(COALESCE(op.name, ""), IF(od.name != "", " - ", ""), COALESCE(od.name, ""))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
