<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\User;

class ExternalController extends Controller
{
    public function SendEmailEndContract(Request $request)
    {
        Config::set('database.default',$request->db_name);
        $params = getEmailConfig();
        $params['view']     = 'email.end-contract';
        $params['subject']  = $params['mail_name'].(get_setting('contract_mail_subject') ? ' - '.get_setting('contract_mail_subject') : '');
        $params['email']    = $request->email;
        $params['cc']       = User::select('email')->whereNotNull('email')->whereNotNull('contract_mail_cc_entitle')->pluck('email')->toArray();
        $params['body']     = str_replace('$name', $request->name, str_replace('$date', $request->end_date_contract, get_setting('contract_mail_body')));
        Config::set('database.default','mysql');
        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
        dispatch($job);
        Config::set('database.default', session('db_name','mysql'));

        return response()->json([
            'status' => 'success',
            'message' => 'Email send successfully'
        ], 200);
    }
}
