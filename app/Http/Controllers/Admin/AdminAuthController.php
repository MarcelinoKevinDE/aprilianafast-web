<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $adminEmail    = config('admin.email');
        $adminPassword = config('admin.password');

        if (
            $request->email === $adminEmail &&
            $request->password === $adminPassword
        ) {
            $request->session()->regenerate();
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('admin_email', $adminEmail);

            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_email']);
        $request->session()->regenerate();

        return redirect()->route('admin.login')->with('status', 'Berhasil logout.');
    }
}

