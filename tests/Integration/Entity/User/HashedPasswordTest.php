<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity\User;

use App\Entity\User;
use App\Entity\User\HashedPassword;
use App\Entity\User\PlainPassword;
use App\Tests\Integration\Entity\User\Fixtures\FakeUserPasswordHasher;
use App\Tests\Integration\Entity\User\Fixtures\UserFactory;
use PHPUnit\Framework\TestCase;

final class HashedPasswordTest extends TestCase
{
    public function testInstantiation(): void
    {
        $plain = $this->createPlainPassword();
        $hashed = new HashedPassword(
            $plain,
            new FakeUserPasswordHasher(),
            UserFactory::createValidUser(),
        );

        self::assertEquals('$12345678', $hashed->__toString());
    }

    private function createPlainPassword(): PlainPassword
    {
        return new PlainPassword('12345678');
    }
}
