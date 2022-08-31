<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class ConnectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->company){
            $config = getCompany($request->company);
            if($config) {
                Config::set('database.default',$config->db_name);
            }
            else{
                return response()->json(
                    [
                        'status' => 'failed',
                        'message'=> "Company is not found"
                    ],
                    404
                );
            }
        }
        return $next($request);
    }
}
