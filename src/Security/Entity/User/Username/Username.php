<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Username;

use App\Security\Entity\User\Contract\Query\FindUserByUsernameQueryInterface;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final readonly class Username implements UsernameInterface
{
    private string $username;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(?string $username)
    {
        Assert::string($username, self::REQUIRED_MESSAGE);
        Assert::notWhitespaceOnly($username, self::REQUIRED_MESSAGE);
        Assert::lengthBetween($username, self::MIN_LENGTH, self::MAX_LENGTH, self::LENGTH_MESSAGE);
        Assert::regex($username, self::PATTERN, self::PATTERN_MESSAGE);

        $this->username = $username;
    }

    public function clean(
        FindUserByUsernameQueryInterface $findUserByUsernameQuery
    ): UniqueUsername {
        return new UniqueUsername($this, $findUserByUsernameQuery);
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
