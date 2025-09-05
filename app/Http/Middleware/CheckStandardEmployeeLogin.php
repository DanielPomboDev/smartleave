<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStandardEmployeeLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is logged in as standard employee, allow access to employee routes only
        if (session('login_as_standard_employee', false)) {
            // Get the current route name
            $routeName = $request->route()->getName();
            
            // Define employee-only routes
            $employeeRoutes = [
                'employee.dashboard',
                'employee.request.leave',
                'employee.settings',
                'employee.profile',
                'employee.leave.history',
                'settings.update',
                'settings.notifications.update'
            ];
            
            // If current route is not in employee routes, redirect to employee dashboard
            if (!in_array($routeName, $employeeRoutes)) {
                return redirect()->route('employee.dashboard');
            }
            
            return $next($request);
        }

        return $next($request);
    }
}