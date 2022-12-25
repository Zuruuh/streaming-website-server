<?php

declare(strict_types=1);

namespace App\Security\UseCase\Register;

use App\Security\Entity\User;
use DateTimeInterface;

final readonly class RegisterOutputDTO
{
    public string $id;
    public string $username;
    public string $email;
    public string $registeredAt;

    /**
     * @var string[] $roles
     */
    public array $roles;

    public function __construct(User $user)
    {
        $this->id = $user->getId()->toRfc4122();
        $this->username = $user->getUsername()->__toString();
        $this->email = $user->getEmail()->__toString();
        $this->registeredAt = $user->getRegisteredAt()->format(DateTimeInterface::ATOM);
        $this->roles = $user->getRoles();
    }
}
