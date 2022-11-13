<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\User\HashedPassword;
use App\Entity\User\PlainPassword;
use App\Entity\User\Username;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';

    #[ORM\Id]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\Embedded(class: Username::class, columnPrefix: false)]
    private Username $username;

    #[ORM\Embedded(class: HashedPassword::class, columnPrefix: false)]
    private HashedPassword $password;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $registeredAt;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    public function __construct(
        Username $username,
        PlainPassword $plainPassword,
        UserPasswordHasherInterface $hasher,
        ClockInterface $clock
    ) {
        $this->id = new Ulid();
        $this->username = $username;
        $this->password = new HashedPassword($plainPassword, $hasher, $this);
        $this->registeredAt = $clock->now();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password->__toString();
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return array_unique([self::ROLE_USER, ...$this->roles]);
    }

    public function eraseCredentials(): void
    {
        // empty method stub
    }

    public function getUserIdentifier(): string
    {
        return $this->username->__toString();
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getRegisteredAt(): DateTimeImmutable
    {
        return $this->registeredAt;
    }
}
