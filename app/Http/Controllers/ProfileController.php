<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('profile_picture')->store('profile-pictures', 'public');

        $user = auth()->user();
        $user->profile_picture = $path;
        $user->save();

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }
}
