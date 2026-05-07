<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        return redirect()->back()->withErrors($validatedUserData)->withInput()->exceptInput('password');
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

    public function edit(Request $request): View
    {
        $userData = User::findOrFail($request->user()->id);
        return view('users.user.edit', ['userData' => $userData]);
    }

    public function submit_edited_user(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->user()->id);

        $validatedUserData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validatedUserData) {
            $updatedUser = $user->updateOrFail([
                'name' => $validatedUserData['name'],
                'email' => $validatedUserData['email'],
                'password' => $validatedUserData['password'],
            ]);
        }

        if ($updatedUser) {
            return redirect(route('user-login-form'));
        }
        return redirect()->back()->withErrors($updatedUser)->withInput()->exceptInput('password');
    }


    public function delete(Request $request): View
    {
        $userData = User::findOrFail($request->user()->id);
        return view('users.user.delete', ['userData' => $userData]);
    }

    public function submit_to_delete_user(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->user()->id);


        $user->delete();
        return redirect(route('user-register-form'));
    }
}
