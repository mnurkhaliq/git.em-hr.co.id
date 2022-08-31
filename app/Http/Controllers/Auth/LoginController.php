<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Validate the user login request.
     *
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        
        if(get_setting('login_with_captcha')==2)
        {
            $this->validate($request, [
                $this->username() => 'required',
                'password'        => 'required',
                'captcha'         => 'required|captcha',
            ]);
        }

        if(get_setting('login_with_captcha')==3)
        {
            $var = "g-recaptcha-response";
            $response = $request->$var;
                                  
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array(
                'secret' => env('RECAPTCHA_SECRET'),
                'response' => $request->$var
            );

            $options = array(
                 'http' => array (
                    'method' => 'POST',
                    'content' => http_build_query($data),
                    'header' =>
                        "Content-Type: application/x-www-form-urlencoded\r\n".
                        "Authorization: Bearer sdf541gs6df51gsd1bsb16etb16teg1etr1ge61g\n",
                 )
            );
            
            $context    = stream_context_create($options);
            $verify     = file_get_contents($url, false, $context);

            $captcha_success=json_decode($verify);
            if ($captcha_success->success==false) 
            {
                $company_url = "/".session('company_url','login');
                return redirect($company_url)->with('error', 'Captcha salah silahkan dicoba kembali.');

            }
        }
        
    }

    /**
     * [credentials description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    protected function credentials(Request $request)
    {
        if($request->p_id){
            $credentials = ['nik'=>$request->get('email'),'password'=>$request->get('password')];
            if($request->p_id)
                $credentials['project_id'] = $request->p_id;
            return $credentials;
//            return ['nik'=>$request->get('email'),'password'=>$request->get('password'),'status'=> NULL];
        }
        return $request->only($this->username(), 'password');
    }
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        // if user have role
        if (auth()->user()->access_id == 1) // Admin
        {
            $user = User::where('nik', auth()->user()->nik)->first();
            $user->last_logged_in_at = date('Y-m-d H:i:s');
            $user->save();
            return $this->redirectTo = '/administrator';
        }
        elseif (auth()->user()->access_id == 2) // karyawan
        {
            $user = User::where('nik', auth()->user()->nik)->first();
            $user->last_logged_in_at = date('Y-m-d H:i:s');
            $user->save();
        
            return $this->redirectTo = '/karyawan';
        }elseif (auth()->user()->access_id == 3)  //super admin client
        {
            return $this->redirectTo = '/superadmin';
        }

        return $this->redirectTo = '/';
    }

    protected function Logout(){
        $user = Auth::user();
        $company_url = "/" . session('company_url', 'login');
        if($user) {
            $nik = $user->nik;
            $user = User::where('nik', $nik)->first();
            $user->last_logged_out_at = date('Y-m-d H:i:s');
            $user->save();
            session()->forget(['company_url', 'db_name']);
            session()->flush();

            Auth::logout();
        }
        return redirect($company_url);
    }

    function authenticated(Request $request, $user)
    {
        \Session::put('is_login_administrator', false);

        if($user->inactive_date && Carbon::now() >= $user->inactive_date){
            Auth::logout();
            return redirect()->back()->withInput()->withErrors(['activate'=>'Your account has been deactivated']);
        // }else if($user->status == 2 && Carbon::now() >= $user->non_active_date){
        //     Auth::logout();
        //     return redirect()->back()->withInput()->withErrors(['activate'=>'You have resigned!']);
        }

        session(['db_name'=>session('user_db', (isset($_COOKIE['db_name']) ? $_COOKIE['db_name'] : 'mysql'))]);
        session(['company_url'=>strtolower(session('temp_company_url', (isset($_COOKIE['company_url']) ? $_COOKIE['company_url'] : 'login')))]);
        setcookie('db_name', session('user_db'));
        setcookie('company_url', strtolower(session('temp_company_url')));
        config()->set('database.default', session('db_name', 'mysql'));
        $user->update([
            'last_logged_in_at' => date('Y-m-d H:i:s')
        //    'npwp_number' => $request->getClientIp()
        ]);
    }
}
