<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::table('setting')->insert(
            array(
                'key' => 'period_ca_pr',
                'value' => 'no',
                'created_at' => now(),
                'updated_at'=> now(),
            )
        );

        $results = DB::table('payment_request_form')->get();
        foreach ($results as $result){
            DB::table('payment_request_form')
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
        Schema::table('setting', function (Blueprint $table) {
            //
        });
    }
}
