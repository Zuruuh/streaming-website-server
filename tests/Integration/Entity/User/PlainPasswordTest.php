<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity\User;

use App\Entity\User\PlainPassword;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PlainPasswordTest extends TestCase
{
    public function testNullPassword(): void
    {
        $this->createPlainPasswordAndExpectErrorMessage(
            null,
            'validators.user.password.required',
        );
    }

    public function testPasswordTooShort(): void
    {
        $this->createPlainPasswordAndExpectErrorMessage(
            str_repeat('a', PlainPassword::MIN_LENGTH - 1),
            'validators.user.password.length',
        );
    }

    public function testPasswordTooLong(): void
    {
        $this->createPlainPasswordAndExpectErrorMessage(
            str_repeat('a', PlainPassword::MAX_LENGTH + 1),
            'validators.user.password.length',
        );
    }

    public function testValidPassword(): void
    {
        $password = new PlainPassword('12345678');

        self::assertEquals('12345678', $password->__toString());
    }

    private function createPlainPasswordAndExpectErrorMessage(
        ?string $plainPassword,
        string $expectedErrorMessage,
    ): void {
        try {
            new PlainPassword($plainPassword);
        } catch (InvalidArgumentException $e) {
            self::assertEquals($expectedErrorMessage, $e->getMessage());
        }
    }
}
