<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity\User\Fixtures;

use App\Contracts\User\Query\CheckUserWithUsernameExistsQueryInterface;
use App\Entity\User\Username;

final class CheckUserWithUsernameExistsQueryMock implements CheckUserWithUsernameExistsQueryInterface
{
    public function __construct(
        private readonly bool $exists = false,
    ) {}

    public function __invoke(Username $username): bool
    {
        return $this->exists;
    }
}
