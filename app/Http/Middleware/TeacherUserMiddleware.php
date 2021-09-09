<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class TeacherUserMiddleware
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
        if(!\Auth::guard('T_USER')->check()){
            return Redirect::route('front_index');
        }        
        return $next($request);
    }
}
