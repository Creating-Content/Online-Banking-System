<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class UserRole
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
         if(Auth::user()->role == 2){
             return $next($request);
           
        }
         abort(403,"You are not authorized to access this page.");
        
    }
}
