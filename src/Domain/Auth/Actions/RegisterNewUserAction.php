<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(
        string $name,
        string $email,
        string $password
    ): User
    {
        $user = User::query()->create([
            'name' => $name,
            'email' => str($email)->lower()->squish()->value(),
            'password' => Hash::make($password),
        ]);

        event(new Registered($user));

        return $user;
    }
}
