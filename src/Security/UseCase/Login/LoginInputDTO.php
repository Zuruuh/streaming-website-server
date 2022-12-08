<?php

declare(strict_types=1);

namespace App\Security\UseCase\Login;

use App\Security\Entity\User\Password\PlainPassword;
use App\Security\Entity\User\Username\AlreadyTakenUsername;

final class LoginInputDTO
{
    public function __construct(
        public readonly AlreadyTakenUsername $username,
        public readonly PlainPassword        $plainPassword,
    ) {}
}
