<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Username;

use App\Security\Entity\User;
use App\Security\Entity\User\Contract\Query\FindUserByUsernameQueryInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;

#[Embeddable]
final class UniqueUsername implements UsernameInterface
{
    #[Column(name: 'username', type: Types::STRING, length: self::MAX_LENGTH, unique: true, nullable: false)]
    private string $username;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(Username $username, FindUserByUsernameQueryInterface $findUserByUsernameQuery)
    {
        if ($findUserByUsernameQuery($username) instanceof User) {
            throw new InvalidArgumentException(self::ALREADY_TAKEN_MESSAGE);
        }

        $this->username = $username->__toString();
    }

    public static function fromString(
        ?string $username,
        FindUserByUsernameQueryInterface $findUserByUsernameQuery
    ): self {
        return (new Username($username))
            ->clean($findUserByUsernameQuery);
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
