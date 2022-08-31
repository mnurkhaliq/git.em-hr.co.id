<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberToPaymentRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_request', function (Blueprint $table) {
            $table->string('number')->nullable()->after('user_id');
        });

        $results = DB::table('payment_request')->join('users', 'users.id', '=', 'payment_request.user_id')->select('payment_request.id', 'payment_request.user_id', 'users.nik', 'payment_request.created_at')->where('number', NULL)->get();
        foreach ($results as $result){
            DB::table('payment_request')
                ->where('id',$result->id)
                ->update([
                    "number" => 'PR-'. date('dmY', strtotime($result->created_at)) .'/'.$result->nik .'-'.checkCountByIdPR($result->user_id, $result->id)
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
        Schema::table('payment_request', function (Blueprint $table) {
            //
        });
    }
}
