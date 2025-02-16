<?php

namespace Domain\Auth\Contracts;

use Domain\Auth\Models\User;

interface RegisterNewUserContract
{
    public function __invoke(
        string $name,
        string $email,
        string $password
    ): User;
}
