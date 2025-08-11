<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        try {
            // Validate the form input
            $request->validate([
                'employee_id' => 'required|string',
                'password' => 'required|string',
            ]);

            // Get the employee ID from the request
            $employeeId = $request->employee_id;

            // Look up the user by employee ID
            $user = User::where('user_id', $employeeId)->first();

            if (!$user) {
                \Log::warning('Login failed - User not found', [
                    'employee_id' => $employeeId
                ]);
                return back()->withInput($request->except('password'))
                    ->withErrors([
                        'employee_id' => 'The employee ID does not exist in our system.',
                    ]);
            }

            // Verify the password
            if (!Hash::check($request->password, $user->password)) {
                \Log::warning('Login failed - Invalid password', [
                    'employee_id' => $employeeId
                ]);
                return back()->withInput($request->except('password'))
                    ->withErrors([
                        'password' => 'The password is incorrect.',
                    ]);
            }

            // Log the successful login
            \Log::info('Login successful', [
                'user_id' => $user->getKey(),
                'user_type' => $user->user_type
            ]);

            // Clear any intended URL to prevent redirect loops
            Auth::login($user);

            // Regenerate session to ensure it's fresh
            $request->session()->regenerate();

            // Log Auth::check() status
            \Log::info('Auth::check() status after login and session regeneration: ' . (Auth::check() ? 'true' : 'false'), ['user_id' => $user->getKey()]);

            // Redirect based on user type
            switch ($user->user_type) {
                case 'employee':
                    return redirect()->route('employee.dashboard');
                case 'hr':
                    return redirect()->route('hr.dashboard');
                case 'department':
                case 'admin':
                    return redirect()->route('department.dashboard');
                case 'mayor':
                    return redirect()->route('mayor.dashboard');
                default:
                    return redirect()->route('employee.dashboard');
            }
        } catch (\Exception $e) {
            \Log::error('Login error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors([
                'error' => 'An unexpected error occurred. Please try again.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show');
    }
}
