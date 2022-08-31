<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSisaPlafondToCashAdvanceForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_advance_form', function (Blueprint $table) {
            $table->string('sisa_plafond')->nullable()->after('plafond');
        });

        $results = DB::table('cash_advance_form')->get();
        foreach ($results as $result){
            DB::table('cash_advance_form')
                ->where('id',$result->id)
                ->where('plafond', '!=', NULL)
                ->where('sisa_plafond', NULL)
                ->update([
                    "sisa_plafond" => $result->plafond
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_advance_form', function (Blueprint $table) {
            //
        });
    }
}
