<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');

    }
    public function store(LoginRequest $request)
    {
        $validated = $request->validated();

        if (auth()->attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            $request->session()->regenerate();

            return redirect()->route('products.index');
        }

        return back()
            ->withErrors([
                'email' => 'These credentials do not match our records.',
            ])
            ->onlyInput('email');
    }
}
