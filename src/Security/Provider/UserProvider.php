<?php

declare(strict_types=1);

namespace App\Security\Provider;

use App\Security\Entity\User\Contract\Query\FindUserByUsernameQueryInterface;
use App\Security\Entity\User\Username\Username;
use InvalidArgumentException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserProvider implements UserLoaderInterface
{
    public function __construct(
        private readonly FindUserByUsernameQueryInterface $findUserByUsernameQuery,
    ) {}

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        try {
            $username = new Username($identifier);
        } catch (InvalidArgumentException) {
            return null;
        }

        return $this->findUserByUsernameQuery->__invoke($username);
    }
}
