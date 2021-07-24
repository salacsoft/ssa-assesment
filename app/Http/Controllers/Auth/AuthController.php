<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //


    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');   // Vue component
    }


    /**
     * @param App\Http\Requests\LoginRequest;
     * @return homepage with user crud
     */
    public function authenticate(LoginRequest $request)
    {
        $credential = $request->only(["email", "password"]);
        if (Auth::attempt($credential)){
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    /**
     * @param App\Http\Requests\Request;
     * @return homepage with login and register
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
