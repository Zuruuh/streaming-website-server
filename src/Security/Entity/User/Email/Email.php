<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Email;

use App\Security\Entity\User\Contract\Query\FindUserByEmailQueryInterface;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final readonly class Email implements EmailInterface
{
    private string $email;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(?string $email)
    {
        Assert::string($email, self::REQUIRED_MESSAGE);
        Assert::notWhitespaceOnly($email, self::REQUIRED_MESSAGE);
        Assert::email($email, self::INVALID_MESSAGE);

        $this->email = $email;
    }

    public function clean(FindUserByEmailQueryInterface $findUserByEmailQuery): UniqueEmail
    {
        return new UniqueEmail($this, $findUserByEmailQuery);
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
