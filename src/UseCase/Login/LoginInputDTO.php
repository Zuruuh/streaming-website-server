<?php

declare(strict_types=1);

namespace App\UseCase\Login;

final class LoginInputDTO
{
    public function __construct(
        public readonly string $username,
        public readonly string $plainPassword,
    ) {}
}
