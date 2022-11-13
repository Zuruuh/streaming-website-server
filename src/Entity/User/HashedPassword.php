<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;
use Stringable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Embeddable]
final class HashedPassword implements Stringable
{
    #[Column(type: Types::STRING, length: 4096)]
    private string $password;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        PlainPassword $plainPassword,
        UserPasswordHasherInterface $hasher,
        User $owner,
    ) {
        $hashed = $hasher->hashPassword($owner, $plainPassword->__toString());

        $this->password = $hashed;
    }

    public function __toString(): string
    {
        return $this->password;
    }
}
