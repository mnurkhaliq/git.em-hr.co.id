<?php

namespace App\Http\Middleware;

use Closure;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $access_id)
    {
        if($request->user()->access_id != $access_id)
        {
            if($request->user()->access_id == 1) {
                if ($access_id == 2) {
                    session(['access'=>'employee']);
                    return $next($request);
                }
                return redirect()->to('administrator')->with('message-error', 'Access Denied');
            }
            elseif($request->user()->access_id == 2)
                return redirect()->to('karyawan')->with('message-error', 'Access Denied');
            elseif($request->user()->access_id == 3)
                return redirect()->to('superadmin')->with('message-error', 'Access Denied');
            else {
                $company_url = "/".session('company_url','');
                session()->forget(['company_url','db_name']);
                session()->flush();
                return redirect()->to($company_url);
            }
        }
        if($access_id == 1){
            session(['access'=>'admin']);
        }
        return $next($request);
    }
}
