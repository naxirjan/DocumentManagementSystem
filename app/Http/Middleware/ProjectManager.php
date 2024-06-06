<?php

namespace App\Http\Middleware;

use Closure;
use URL;
use Auth;
class ProjectManager
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
        if(!(Auth::check() && session()->get('current_active_role_id') == 5)){
            return redirect(URL::previous());
        }

        return $next($request);
    }
}
