<?php

declare(strict_types=1);

namespace App\Security\UseCase\Register;

use App\Security\Entity\User;
use App\Security\Entity\User\Password\PlainPassword;
use App\Security\Entity\User\Username\UniqueUsername;
use App\Security\Entity\User\Email\UniqueEmail;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class RegisterInputDTO
{
    public function __construct(
        public UniqueUsername $username,
        public UniqueEmail $email,
        public PlainPassword  $plainPassword,
    ) {}

    public function toUser(
        UserPasswordHasherInterface $passwordHasher,
        ClockInterface $clock,
    ): User {
        return new User(
            $this->username,
            $this->email,
            $this->plainPassword,
            $passwordHasher,
            $clock,
        );
    }
}
