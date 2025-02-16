<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function page(): View
    {
        return view('auth.forgot-password');
    }

    public function handle(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email:dns|exists:users,email'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            flash()->info(__($status));
            return back();
        } else {
            flash()->alert(__($status));
            return back()->withErrors(['email' => __($status)]);
        }
    }
}
