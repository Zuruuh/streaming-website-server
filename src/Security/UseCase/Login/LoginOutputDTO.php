<?php

declare(strict_types=1);

namespace App\Security\UseCase\Login;

use App\Security\Entity\User;

final class LoginOutputDTO
{
    public readonly string $id;
    public readonly string $username;

    /**
     * @var string[] $roles
     */
    public readonly array $roles;

    public function __construct(User $user) {
        $this->id = $user->getId()->toRfc4122();
        $this->username = $user->getUserIdentifier();
        $this->roles = $user->getRoles();
    }
}
