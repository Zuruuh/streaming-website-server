<?php

declare(strict_types=1);

namespace App\Security\UseCase\Login;

use App\Security\Entity\User\Password\PlainPassword;
use App\Security\Entity\User\Username\AlreadyTakenUsername;

final readonly class LoginInputDTO
{
    public function __construct(
        public AlreadyTakenUsername $username,
        public PlainPassword        $plainPassword,
    ) {}
}
