<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Clear any intended URL to prevent redirect loops
            $request->session()->put('url.intended', null);

            if ($user->user_type === 'hr') {
                return redirect()->route('hr.dashboard');
            } elseif ($user->user_type === 'department_admin') {
                return redirect()->route('department.dashboard');
            } elseif ($user->user_type === 'mayor') {
                return redirect()->route('mayor.dashboard');
            } else {
                return redirect()->route('employee.dashboard');
            }
        }

        // Clear any intended URL when redirecting to login
        $request->session()->put('url.intended', null);

        return $next($request);
    }
}
