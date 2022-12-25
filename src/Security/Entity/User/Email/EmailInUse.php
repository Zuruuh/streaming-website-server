<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Email;

use App\Security\Entity\User;
use App\Security\Entity\User\Contract\Query\FindUserByEmailQueryInterface;
use InvalidArgumentException;

final readonly class EmailInUse implements EmailInterface
{
    private string $email;
    public User $user;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(Email $email, FindUserByEmailQueryInterface $findUserByEmailQuery)
    {
        $user = $findUserByEmailQuery($email);

        if (!($user instanceof User)) {
            throw new InvalidArgumentException(self::NOT_IN_USE_MESSAGE);
        }

        $this->user = $user;
        $this->email = $email->__toString();
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
