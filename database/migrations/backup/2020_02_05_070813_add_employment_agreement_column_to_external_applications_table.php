<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmploymentAgreementColumnToExternalApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_applications', function (Blueprint $table) {
            //
            $table->string('employment_agreement_number')->nullable()->after('offering_letter_signing_date');
            $table->date('employment_agreement_date')->nullable()->after('employment_agreement_number');
            $table->date('employment_agreement_signing_date')->nullable()->after('employment_agreement_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_applications', function (Blueprint $table) {
            //
        });
    }
}
