<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class IsNotActive
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
        if(!(Auth::check() && Auth::user()->status == "Active")){
            Auth::logout();
            return redirect("/")->with("Msg","You Are Deactive For Particular Time");
        }
        return $next($request);
        
    }
}
