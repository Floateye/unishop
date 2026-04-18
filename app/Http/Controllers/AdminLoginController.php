<?php

namespace App\Http\Controllers;

class AdminLoginController extends Controller
{
    public function create()
    {
        return view('admin-login');
    }
    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($attributes)) {
            session()->regenerate();
            return redirect()->route('admin.index')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Your provided credentials could not be verified.']);
    }
}