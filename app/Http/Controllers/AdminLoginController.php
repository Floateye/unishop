<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

class AdminLoginController extends Controller
{
    public function create()
    {
        return view('auth.admin.login');
    }
    public function store(LoginRequest $request)
    {
        $validated = $request->validated();

        if (auth()->attempt($validated)) {
            session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Your provided credentials could not be verified.']);
    }
}
