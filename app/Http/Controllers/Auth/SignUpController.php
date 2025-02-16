<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password as PasswordValidation;

class SignUpController extends Controller
{
    public function page(): View
    {
        return view('auth.signup');
    }

    public function handle(Request $request, RegisterNewUserContract $action): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email:dns|unique:users',
            'password' => ['required', 'confirmed', PasswordValidation::default()],
        ]);

        //TODO: make DTOs
        $user = $action(
            $data['name'],
            $data['email'],
            $data['password']
        );

        auth()->login($user);

        return redirect()->route('home');
    }
}
