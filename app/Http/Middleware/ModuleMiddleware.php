<?php

namespace App\Http\Middleware;

use Closure;

class ModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $module)
    {
        if(!checkModule($module) || !checkModuleAdmin($module)){
            return redirect()->back()->with('message-error', 'You have no access to this module');
        }
        return $next($request);
    }
}
