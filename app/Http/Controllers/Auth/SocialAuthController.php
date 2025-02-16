<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirect(string $driver = 'github'): RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();
        } catch (Throwable) {
            throw new DomainException('Произошла ошибка при попытке входа через ' . $driver);
        }
    }

    public function callback(string $driver = 'github'): RedirectResponse
    {
        if ($driver !== 'github') {
            throw new DomainException('Драйвер ' . $driver . ' не поддерживается');
        }

        $githubUser = Socialite::driver($driver)->user();

        $user = User::query()->updateOrCreate([
            $driver . '_id' => $githubUser->id,
        ], [
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'password' => Hash::make(str()->random(24)),
        ]);

        auth()->login($user);

        return redirect()->intended();
    }
}
