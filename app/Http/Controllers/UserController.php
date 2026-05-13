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
            User::create([
                'name' => $validatedUserData['name'],
                'email' => $validatedUserData['email'],
                'password' => $validatedUserData['password'],
            ]);
            return redirect(route('user-login-form'))->with('registration_success', true);
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
            $user = Auth::user();
            $request->session()->regenerate();
            $request->session()->put('login_success', true);
            $request->session()->save();
            return redirect(route('user-dashboard', ['user' => $user->id]));
            // return redirect(route('user-dashboard', ['user' => $user->id]))->with('login_success', true);
        }
        return redirect()->back()->withErrors(['email' => 'We do not have the entered credentials'])->withInput()->exceptInput('password');
    }

    public function dashboard(User $user): View
    {
        $userData = User::findOrFail($user->id);
        return view('users.user.dashboard', ['userData' => $userData]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('user-login-form'));
    }

    public function edit(User $user): View
    {
        $userData = User::findOrFail($user->id);
        return view('users.user.edit', ['user' => $userData]);
    }

    public function submit_edited_user(Request $request, User $user): RedirectResponse
    {
        $user = User::findOrFail($user->id);

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
            return redirect(route('user-login-form'))->with('update_success', true);
        }
        return redirect()->back()->withErrors($updatedUser)->withInput()->exceptInput('password');
    }


    public function delete(User $user): View
    {
        $userData = User::findOrFail($user->id);
        return view('users.user.delete', ['user' => $userData]);
    }

    public function submit_to_delete_user(Request $request, User $user): RedirectResponse
    {
        $user = User::findOrFail($user->id);


        $user->delete();

        return redirect(route('user-register-form'))->with('delete_success', true);
    }
}
