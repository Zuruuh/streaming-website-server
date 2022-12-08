<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Contract\Persister;

use App\Security\Entity\User;

interface UserPersisterInterface
{
    public function save(User $user): void;
}
