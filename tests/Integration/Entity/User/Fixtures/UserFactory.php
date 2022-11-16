<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity\User\Fixtures;

use App\Entity\User;
use App\Entity\User\PlainPassword;
use App\Entity\User\Username;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

final class UserFactory
{
    public static function createValidUser(
        Username $username = null,
        PlainPassword $plainPassword = null,
        FakeUserPasswordHasher $passwordHasher = new FakeUserPasswordHasher(),
        ClockInterface $clock = new NativeClock(),
    ): User {
        $username ??= new Username('username', new CheckUserWithUsernameExistsQueryMock(false));
        $plainPassword ??= new PlainPassword('12345678');

        return new User(
            $username,
            $plainPassword,
            $passwordHasher,
            $clock
        );
    }
}
