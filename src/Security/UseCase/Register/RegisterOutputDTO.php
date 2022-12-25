<?php

declare(strict_types=1);

namespace App\Security\UseCase\Register;

use App\Security\Entity\User;
use DateTimeInterface;

final readonly class RegisterOutputDTO
{
    public string $id;
    public string $username;
    public string $registeredAt;

    public function __construct(User $user)
    {
        $this->id = $user->getId()->toRfc4122();
        $this->username = $user->getUsername()->__toString();
        $this->registeredAt = $user->getRegisteredAt()->format(DateTimeInterface::ATOM);
    }
}
