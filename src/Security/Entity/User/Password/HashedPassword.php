<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Password;

use App\Security\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Embeddable]
final class HashedPassword implements PasswordInterface
{
    #[Column(name: 'password', type: Types::STRING, length: 1024, nullable: false)]
    protected string $password;

    public function __construct(
        PlainPassword               $plainPassword,
        UserPasswordHasherInterface $passwordHasher,
        User                        $user,
    ) {
        $this->password = $passwordHasher->hashPassword($user, $plainPassword->__toString());
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromString(
        ?string $plainPassword,
        UserPasswordHasherInterface $passwordHasher,
        User $user
    ): self {
        return new self(
            new PlainPassword($plainPassword),
            $passwordHasher,
            $user
        );
    }

    public function __toString(): string
    {
        return $this->password;
    }
}
