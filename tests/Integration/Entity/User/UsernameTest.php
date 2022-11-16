<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity\User;

use App\Contracts\User\Query\CheckUserWithUsernameExistsQueryInterface;
use App\Entity\User\Username;
use App\Tests\Integration\Entity\User\Fixtures\CheckUserWithUsernameExistsQueryMock;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class UsernameTest extends TestCase
{

    public function testNullUsername(): void
    {
        $this->createUsernameAndExpectErrorMessage(
            null,
            'validators.user.username.required'
        );
    }

    public function testUsernameTooShort(): void
    {
        $this->createUsernameAndExpectErrorMessage(
            str_repeat('a', Username::MIN_LENGTH - 1),
            'validators.user.username.length',

        );
    }

    public function testUsernameTooLong(): void
    {
        $this->createUsernameAndExpectErrorMessage(
            str_repeat('e', Username::MAX_LENGTH + 1),
            'validators.user.username.length',
        );
    }

    public function testUsernameDoesNotPassRegex(): void
    {
        $this->createUsernameAndExpectErrorMessage(
            'username$',
            'validators.user.username.regex'
        );
    }

    public function testUsernameIsAlreadyTaken(): void
    {
        $this->createUsernameAndExpectErrorMessage(
            'username',
            'validators.user.username.already_in_use',
            new CheckUserWithUsernameExistsQueryMock(true),
        );
    }

    public function testValidUsername(): void
    {
        $username = new Username('username', new CheckUserWithUsernameExistsQueryMock());

        self::assertEquals('username', $username->__toString());
    }

    private function createUsernameAndExpectErrorMessage(
        ?string $username,
        string $expectedErrorMessage,
        CheckUserWithUsernameExistsQueryInterface $query = new CheckUserWithUsernameExistsQueryMock(),
    ): void {
        try {
            new Username($username, $query);
        } catch (InvalidArgumentException $e) {
            self::assertEquals($expectedErrorMessage, $e->getMessage());
        }
    }
}
