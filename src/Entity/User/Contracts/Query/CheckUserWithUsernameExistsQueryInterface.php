<?php

declare(strict_types=1);

namespace App\Entity\User\Contracts\Query;

use App\Entity\User\Username;

interface CheckUserWithUsernameExistsQueryInterface
{
    public function __invoke(Username $username): bool;
}
