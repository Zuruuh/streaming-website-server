<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Contracts\User\Query\CheckUserWithUsernameExistsQueryInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;
use Stringable;
use Webmozart\Assert\Assert;

#[Embeddable]
final class Username implements Stringable
{
    public const MIN_LENGTH = 3;
    public const MAX_LENGTH = 32;
    public const REGEX = '/^[a-zA-Z][a-zA-Z0-9\-_]+$/';

    #[Column(type: Types::STRING, length: self::MAX_LENGTH, unique: true)]
    private string $username;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(?string $username, CheckUserWithUsernameExistsQueryInterface $query)
    {
        Assert::string($username, 'validators.user.username.required');
        Assert::lengthBetween($username, self::MIN_LENGTH, self::MAX_LENGTH, 'validators.user.username.length');
        Assert::regex($username, self::REGEX, 'validators.user.username.regex');

        $this->username = $username;

        $alreadyInUse = $query($this);
        if ($alreadyInUse) {
            throw new InvalidArgumentException('validators.user.username.already_in_use');
        }
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
