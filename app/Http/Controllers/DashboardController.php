<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {

        return view('auth.admin.dashboard');
    }
}
