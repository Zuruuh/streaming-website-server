<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Password;

use App\Security\Entity\User;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Webmozart\Assert\Assert;

final class PlainPassword implements PasswordInterface
{
    protected string $value;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(?string $plainPassword)
    {
        Assert::string($plainPassword, 'validators.user.password.required');
        Assert::notWhitespaceOnly($plainPassword, 'validators.user.password.required');
        Assert::lengthBetween($plainPassword, self::MIN_LENGTH, self::MAX_LENGTH, 'validators.user.password.length');

        $this->value = $plainPassword;
    }

    public function hash(UserPasswordHasherInterface $passwordHasher, User $user): HashedPassword
    {
        return new HashedPassword($this, $passwordHasher, $user);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
