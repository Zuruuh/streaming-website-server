<?php

declare(strict_types=1);

namespace App\Security\Provider;

use App\Security\Entity\User;
use App\Security\Entity\User\Contract\Query\FindUserByIdQueryInterface;
use InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Uid\Ulid;

final readonly class UserProvider implements UserProviderInterface
{
    public function __construct(
        private FindUserByIdQueryInterface $findUserByIdQuery,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        try {
            $id = Ulid::fromRfc4122($identifier);
        } catch (InvalidArgumentException) {
            throw new UserNotFoundException();
        }

        $user = $this->findUserByIdQuery->__invoke($id);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }
}
