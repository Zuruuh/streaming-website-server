<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity\User;

use App\Entity\User;
use App\Entity\User\PlainPassword;
use App\Entity\User\Username;
use App\Tests\Integration\Entity\User\Fixtures\CheckUserWithUsernameExistsQueryMock;
use App\Tests\Integration\Entity\User\Fixtures\UserFactory;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;

final class UserTest extends TestCase
{
    public function testGetId(): void
    {
        $user = UserFactory::createValidUser();
        $id = (bool) $user->getId();

        self::assertTrue($id);
    }

    public function testGetPassword(): void
    {
        $user = UserFactory::createValidUser(plainPassword: new PlainPassword('12345678'));

        self::assertEquals('$12345678', $user->getPassword());
    }

    public function testGetRoles(): void
    {
        $user = UserFactory::createValidUser();

        self::assertEquals([User::ROLE_USER], $user->getRoles());
    }

    public function testGetUsername(): void
    {
        $username = new Username('username', new CheckUserWithUsernameExistsQueryMock());
        $user = UserFactory::createValidUser(username: $username);

        self::assertEquals('username', $user->getUserIdentifier());
        self::assertEquals($username, $user->getUsername());
    }

    public function testGetRegisteredAt(): void
    {
        $clock = new MockClock(new DateTimeImmutable());
        $user = UserFactory::createValidUser(clock: $clock);

        self::assertEquals($clock->now(), $user->getRegisteredAt());
    }
}
