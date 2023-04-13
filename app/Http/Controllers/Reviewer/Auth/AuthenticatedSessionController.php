<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Plugins\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Torann\GeoIP\Facades\GeoIP;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Reviewer\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('reviewer.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Reviewer\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::guard('reviewer')->user();
        $user->login_at = Carbon::now();
        $user->timezone = Timezone::getTimezone($request->ip()) ?? 'Asia/Kuala_Lumpur';
        $user->save();

        return redirect(route('reviewer.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('reviewer')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
