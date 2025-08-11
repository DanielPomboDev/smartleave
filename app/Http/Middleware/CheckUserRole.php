<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login.show');
        }

        if (Auth::user()->user_type !== $role) {
            // Redirect to appropriate dashboard based on actual user role
            if (Auth::user()->user_type === 'hr') {
                return redirect('/hr-dashboard');
            } elseif (Auth::user()->user_type === 'department_admin') {
                return redirect('/department-dashboard');
            } elseif (Auth::user()->user_type === 'mayor') {
                return redirect('/mayor-dashboard');
            } else {
                return redirect('/employee-dashboard');
            }
        }

        return $next($request);
    }
}
