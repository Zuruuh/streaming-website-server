<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Username;

use App\Security\Entity\User;
use App\Security\Entity\User\Contract\Query\FindUserByUsernameQueryInterface;
use InvalidArgumentException;

final class AlreadyTakenUsername implements UsernameInterface
{
    private string $username;

    public function __construct(Username $username, FindUserByUsernameQueryInterface $findUserByUsernameQuery)
    {
        if (!($findUserByUsernameQuery($username) instanceof User)) {
            throw new InvalidArgumentException('validators.user.username.not_in_use');
        }

        $this->username = $username->__toString();
    }

    public static function fromString(
        ?string $username,
        FindUserByUsernameQueryInterface $findUserByUsernameQuery
    ): self {
        return new self(
            new Username($username),
            $findUserByUsernameQuery
        );
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
