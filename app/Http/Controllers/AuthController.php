<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $validUsername     = config('admin.username');
        $validPasswordHash = config('admin.password_hash');

        if (
            $validUsername && $validPasswordHash
            && hash_equals($validUsername, $request->input('username'))
            && Hash::check($request->input('password'), $validPasswordHash)
        ) {
            $request->session()->regenerate();
            $request->session()->put('is_admin', true);
            return redirect()->intended(route('manage.index'));
        }

        return back()
            ->withErrors(['login' => 'Invalid username or password.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('is_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
