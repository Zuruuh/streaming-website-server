<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Email;

use App\Security\Entity\User;
use App\Security\Entity\User\Contract\Query\FindUserByEmailQueryInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;

#[Embeddable]
final class UniqueEmail implements EmailInterface
{
    #[Column(name: 'email', type: Types::STRING, unique: true, nullable: false)]
    private string $email;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(Email $email, FindUserByEmailQueryInterface $findUserByEmailQuery)
    {
        if ($findUserByEmailQuery($email) instanceof User) {
            throw new InvalidArgumentException(self::ALREADY_IN_USE_MESSAGE);
        }

        $this->email = $email->__toString();
    }

    public static function fromString(
        ?string $email,
        FindUserByEmailQueryInterface $findUserByEmailQuery,
    ): self {
        return (new Email($email))
            ->clean($findUserByEmailQuery);
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
