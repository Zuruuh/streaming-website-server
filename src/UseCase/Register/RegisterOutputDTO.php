<?php

declare(strict_types=1);

namespace App\UseCase\Register;

use App\Entity\User;

final class RegisterOutputDTO
{
    public readonly string $id;
    public readonly string $username;

    public function __construct(
        User $user,
    ) {
        $this->username = $user->getUsername()->__toString();
        $this->id = $user->getId()->toBase32();
    }
}
