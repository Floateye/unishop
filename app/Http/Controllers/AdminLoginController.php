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
            if (!auth()->user()->hasRole('Admin')) {
                auth()->logout();
                session()->invalidate();
                return back()->withErrors(['email' => 'This is a customer account. Please use the customer login page.']);
            }
            session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Your provided credentials could not be verified.']);
    }
}
