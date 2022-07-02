<?php

namespace App\Http\Middleware;

use Closure;

class DoctorAuthenticate
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
        if (!auth()->user()->role == 2 ) {
            return redirect('/manager');
        }
        return $next($request);
    }
}
