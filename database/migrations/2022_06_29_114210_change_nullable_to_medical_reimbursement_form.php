<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNullableToMedicalReimbursementForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_reimbursement_form', function (Blueprint $table) {
            $table->string('jenis_klaim')->nullable()->change();
            $table->string('jumlah')->nullable()->change();
            $table->string('medical_type_id')->nullable()->change();
            $table->string('no_kwitansi')->nullable()->change();
            $table->string('kuota_plafond')->nullable()->change();
            $table->string('plafond_terpakai')->nullable()->change();
            $table->string('plafond_sisa')->nullable()->change();
            $table->text('note_approval')->nullable()->after('nominal_approve');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_reimbursement_form', function (Blueprint $table) {
            //
        });
    }
}
