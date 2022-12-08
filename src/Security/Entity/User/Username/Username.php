<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Username;

use App\Security\Entity\User\Contract\Query\FindUserByUsernameQueryInterface;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final class Username implements UsernameInterface
{
    private string $username;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(?string $username)
    {
        Assert::string($username, 'validators.user.username.required');
        Assert::notWhitespaceOnly($username, 'validators.user.username.required');
        Assert::lengthBetween($username, self::MIN_LENGTH, self::MAX_LENGTH, 'validators.user.username.length');
        Assert::regex($username, self::PATTERN, 'validators.user.username.pattern');

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
