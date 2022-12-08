<?php

declare(strict_types=1);

namespace App\Security\Entity;

use App\Security\Entity\User\Password\HashedPassword;
use App\Security\Entity\User\Password\PlainPassword;
use App\Security\Entity\User\Username\UniqueUsername;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

#[Entity]
#[Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Id]
    #[Column(type: 'ulid', unique: true, updatable: false)]
    private Ulid $ulid;

    #[Embedded(class: UniqueUsername::class, columnPrefix: false)]
    private UniqueUsername $username;

    #[Embedded(class: HashedPassword::class, columnPrefix: false)]
    private HashedPassword $password;

    /**
     * @var string[] $roles
     */
    #[Column(type: Types::JSON)]
    private array $roles = [];

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $registeredAt;

    public function __construct(
        UniqueUsername $username,
        PlainPassword $password,
        UserPasswordHasherInterface $userPasswordHasher,
        ClockInterface $clock,
    ) {
        $this->ulid = new Ulid();
        $this->username = $username;
        $this->password = $password->hash($userPasswordHasher, $this);
        $this->registeredAt = $clock->now();
    }

    public function getId(): Ulid
    {
        return $this->ulid;
    }

    public function getUsername(): UniqueUsername
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password->__toString();
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string
    {
        return $this->username->__toString();
    }

    public function getRegisteredAt(): DateTimeImmutable
    {
        return $this->registeredAt;
    }
}
