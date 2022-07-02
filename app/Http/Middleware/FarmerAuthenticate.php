<?php

namespace App\Http\Middleware;

use Closure;

class FarmerAuthenticate
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
        if (!auth()->user()->role == 1 ) {
            return redirect(route('diagnosis.index.doctor'));
        }
        return $next($request);
    }
}
