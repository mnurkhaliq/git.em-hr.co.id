<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CrmCommentUser;
use Laravel\Socialite\Facades\Socialite;
use Session;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }
 
    public function callback()
    {
 
        // jika user masih login lempar ke home
        // if (Auth::check()) {
        //     return redirect('/home');
        // }
 
        $oauthUser = Socialite::driver('google')->stateless()->user();

        $data = CrmCommentUser::where('email', $oauthUser->email)->first();
        $comment_user = new CrmCommentUser();
        if(!$data){
            $comment_user->username = $oauthUser->name;
            $comment_user->email = $oauthUser->email;
            $comment_user->phone = '';
            $comment_user->position = '';
            $comment_user->save(); 
        }
        Session::put('user', [
            'isSessionUser' => true,
            'name' => $oauthUser->name,
            'email' => $oauthUser->email,
        ]);

        $currentURL = Session::get('redirectUrl');
        if($currentURL){
            return redirect($currentURL);
        }else{
            return redirect('/');
        }
        // dd($currentURL);

        // return redirect('/');

        // return redirect()->back()->with('success', 'Google Login Success');   

        // $user = User::where('google_id', $oauthUser->id)->first();
        // return redirect('/home');
        // if ($user) {
        //     Auth::loginUsingId($user->id);
        //     return redirect('/home');
        // } else {
        //     $newUser = User::create([
        //         'name' => $oauthUser->name,
        //         'email' => $oauthUser->email,
        //         'google_id'=> $oauthUser->id,
        //         // password tidak akan digunakan ;)
        //         'password' => md5($oauthUser->token),
        //     ]);
        //     Auth::login($newUser);
        //     return redirect('/home');
        // }
    }
}
