<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Contract\Query;

use App\Security\Entity\User;
use App\Security\Entity\User\Username\Username;

interface FindUserByUsernameQueryInterface
{
    public function __invoke(Username $username): ?User;
}
