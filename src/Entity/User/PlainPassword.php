<?php

declare(strict_types=1);

namespace App\Entity\User;

use Stringable;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final class PlainPassword implements Stringable
{
    public const MIN_LENGTH = 4;
    public const MAX_LENGTH = 64;

    private readonly string $plainPassword;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(?string $value)
    {
        Assert::string($value, 'validators.user.password.required');
        Assert::lengthBetween($value, self::MIN_LENGTH, self::MAX_LENGTH, 'validators.user.password.length');

        $this->plainPassword = $value;
    }

    public function __toString()
    {
        return $this->plainPassword;
    }
}
