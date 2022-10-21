<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if(in_array('super_admin', $guards, true) && Auth::guard('super_admin')->check()) {
            return redirect(route('super_admin.dashboard'));
        }
        if(in_array('participant', $guards, true) && Auth::guard('participant')->check()) {
            return redirect(route('participant.dashboard'));
        }
        if(in_array('reviewer', $guards, true) && Auth::guard('reviewer')->check()) {
            return redirect(route('reviewer.dashboard'));
        }
        if(in_array('admin', $guards, true) && Auth::guard('admin')->check()) {
            return redirect(route('admin.dashboard'));
        }
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
