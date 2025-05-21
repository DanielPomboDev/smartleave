<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{
    public function debugAuth()
    {
        $debugInfo = [
            'auth_check' => Auth::check(),
            'user_id' => Auth::id(),
            'user' => Auth::user() ? Auth::user()->toArray() : null,
            'session_id' => session()->getId(),
            'session_data' => session()->all(),
            'request_data' => request()->all(),
        ];
        
        return response()->json($debugInfo);
    }
}
