<?php

namespace App\Http\Middleware;

use Closure;
use URL;
use Auth;
class USManager
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
        if(!(Auth::check() && session()->get('current_active_role_id') == 1)){
            return redirect(URL::previous());
        }
        return $next($request);
    }
}
