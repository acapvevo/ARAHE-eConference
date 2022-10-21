<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if(! $request->expectsJson() && $request->is('super_admin*')) {
            return route('super_admin.login');
        }
        if(! $request->expectsJson() && $request->is('participant*')) {
            return route('participant.login');
        }
        if(! $request->expectsJson() && $request->is('reviewer*')) {
            return route('reviewer.login');
        }
        if(! $request->expectsJson() && $request->is('admin*')) {
            return route('admin.login');
        }
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
