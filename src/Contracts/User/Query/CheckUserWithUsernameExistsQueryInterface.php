<?php

declare(strict_types=1);

namespace App\Contracts\User\Query;

use App\Entity\User\Username;
use App\Shared\Service\ServiceInterface;

interface CheckUserWithUsernameExistsQueryInterface extends ServiceInterface
{
    public function __invoke(Username $username): bool;
}
