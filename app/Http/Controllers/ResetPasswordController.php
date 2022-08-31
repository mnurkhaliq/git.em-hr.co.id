<?php

namespace App\Http\Controllers;

use App\Models\ConfigDB;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    //

    public function index($company='')
    {

        $config = ConfigDB::where('company_code',strtolower($company))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
        if(!$config){
            return abort(404);
        }
        else{
            $user = Auth::user();
            if($user){
                if($user->access_id == 1)
                    return redirect('/administrator');
                else if($user->access_id == 2)
                    return redirect('/karyawan');
            }
            return view('auth.reset-password',compact('company'));
        }
    }

    public function requestReset(Request $request){

        $validator = Validator::make(request()->all(), [
            'nik' => "required"
        ]);
        if($validator->fails())
            return redirect()->back()->withInput()->withErrors($validator->errors());

        $connection = 'mysql';
        if(!empty($request->company)){
            $config = ConfigDB::where('company_code',strtolower($request->company))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
            if($config){
                $connection = $config->db_name;
            }
            else {
                return abort(404);
            }
        }
        $user          = (new User())->on($connection)->where(['nik'=> $request->nik])->first();
        if(!$user)
            return redirect()->back()->withInput()->withErrors(['NIK is not found']);
        else if(empty($user->email))
            return redirect()->back()->withInput()->withErrors(['Your email has not been registered yet! Please contact admin!']);

        $user->password_reset_token = Str::random(32);
        $user->save();
        $params['user']    = $user;
        $params['company'] = $request->company;
        if($user->email != "")
        {
            try {
                \Mail::send('email.reset-password', $params,
                    function ($message) use ($user) {
                        $message->to($user->email);
                        $message->subject('Em-HR Password Reset');
                    }
                );
            }catch (\Swift_TransportException $e) {
                return redirect()->back()->with('message-error', 'Email config is invalid!');
            }
        }
        return redirect()->back()->with('message-success', 'Your password reset request has been sent to your email!');
    }

    public function reset($id,$company='')
    {
        $user = Auth::user();
        if($user){
            if($user->access_id == 1)
                return redirect('/administrator');
            else if($user->access_id == 2)
                return redirect('/karyawan');
        }
        $connection = 'mysql';
        if($company!=''){
            $config = ConfigDB::where('company_code',strtolower($company))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
            if($config){
                $connection = $config->db_name;
            }
            else {
                return abort(404);
            }
        }


        $data                   = (new User())->on($connection)->where(['password_reset_token'=> $id])->first();
        if($data) {
            return view('auth.reset-new-password',compact(['data','company']));
        }
        else{
            return abort(404);
        }
    }

    public function resetPassword(Request $request){
        $this->validate($request,[
            'token'               => 'required',
            'password' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'confirm'      => 'same:password',
        ],
            [
                'password.regex' => 'Password should contain : Lowercase(s), Uppercase(s), Number(s), Symbol!'
            ]);
        $connection = 'mysql';
        if(!empty($request->company)){
            $config = ConfigDB::where('company_code',strtolower($request->company))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
            if($config){
                $connection = $config->db_name;
            }
            else {
                return abort(404);
            }
        }
        $user          = (new User())->on($connection)->where(['password_reset_token'=> $request->token])->first();
        if(!$user)
            return redirect()->back()->withInput()->withErrors(['Token is not found']);

        $user->password             = bcrypt($request->password);
        $user->password_reset_token = null;
        $user->last_change_password = date('Y-m-d H:i:s');
        $user->save();

        return redirect()->to("/".($request->company?$request->company:"login"))->with('message-success', 'Your password has successfully updated!');
    }
}
