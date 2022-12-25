<?php

declare(strict_types=1);

namespace App\Security\Entity;

use App\Security\Entity\User\Password\HashedPassword;
use App\Security\Entity\User\Password\InvalidPasswordException;
use App\Security\Entity\User\Password\PlainPassword;
use App\Security\Entity\User\Username\UniqueUsername;
use App\Security\Token\Generator\AuthTokenGeneratorInterface;
use App\Security\Token\Model\AuthToken;
use App\Security\Token\Persister\AuthTokenPersisterInterface;
use App\Security\Token\Persister\Exception\AuthTokenPersistenceException;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

#[Entity]
#[Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';

    #[Id]
    #[Column(name: 'ulid', type: UlidType::NAME, unique: true, updatable: false)]
    private readonly Ulid $ulid;

    #[Embedded(class: UniqueUsername::class, columnPrefix: false)]
    private UniqueUsername $username;

    #[Embedded(class: HashedPassword::class, columnPrefix: false)]
    private HashedPassword $password;

    /**
     * @var string[] $roles
     */
    #[Column(name: 'roles', type: Types::JSON)]
    private array $roles = [];

    #[Column(name: 'registered_at', type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $registeredAt;

    public function __construct(
        UniqueUsername $username,
        PlainPassword $password,
        UserPasswordHasherInterface $userPasswordHasher,
        ClockInterface $clock,
    ) {
        $this->ulid = new Ulid();
        $this->username = $username;
        $this->password = $password->hash($this, $userPasswordHasher);
        $this->registeredAt = $clock->now();
    }

    // Behaviour

    /**
     * @throws InvalidPasswordException
     * @throws AuthTokenPersistenceException
     */
    public function authenticate(
        PlainPassword $plainPassword,
        UserPasswordHasherInterface $userPasswordHasher,
        AuthTokenGeneratorInterface $authTokenGenerator,
        AuthTokenPersisterInterface $authTokenPersister
    ): AuthToken {
        if (!$plainPassword->isValidForUser($this, $userPasswordHasher)) {
            throw new InvalidPasswordException();
        }

        $token = $authTokenGenerator->forUser($this);
        $authTokenPersister->__invoke($token);

        return $token;
    }

    // Getters

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
        return array_unique([self::ROLE_USER, ...$this->roles]);
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
