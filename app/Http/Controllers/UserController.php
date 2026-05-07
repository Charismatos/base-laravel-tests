<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Http\Attributes\RedirectToRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function register(): View
    {
        return view('users.user.register');
    }


    public function store_user(Request $request): RedirectResponse
    {
        $validatedUserData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validatedUserData) {
            return redirect(route('user-login-form'));
        }
        return redirect()->back()->withErrors($validatedUserData)->withInput();
    }

    public function login(): View
    {
        return view('users.user.login');
    }


    public function submit_user_login(Request $request): RedirectResponse
    {
        $validatedUserCredentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt($validatedUserCredentials)) {
            $request->session()->regenerate();
            return redirect(route('home'));
        }
        return redirect()->back()->withErrors(['email' => 'We do not have the entered credentials'])->withInput()->exceptInput('password');
    }
}
