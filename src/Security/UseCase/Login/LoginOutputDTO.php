<?php

declare(strict_types=1);

namespace App\Security\UseCase\Login;

use App\Security\Entity\User;
use DateTimeInterface;

final readonly class LoginOutputDTO
{
    public string $id;
    public string $username;

    /**
     * @var string[] $roles
     */
    public array $roles;

    public string $registeredAt;

    public function __construct(User $user) {
        $this->id = $user->getId()->toRfc4122();
        $this->username = $user->getUserIdentifier();
        $this->roles = $user->getRoles();
        $this->registeredAt = $user->getRegisteredAt()->format(DateTimeInterface::ATOM);
    }
}
