<?php

declare(strict_types=1);

namespace App\UseCase\Register;

use App\Entity\User\PlainPassword;
use App\Entity\User\Username;

final class RegisterInputDTO
{
    public function __construct(
        public readonly Username $username,
        public readonly PlainPassword $plainPassword,
    ) {}
}
