<?php

declare(strict_types=1);

namespace App\Entity\User\Contracts\Persistence;

use App\Entity\User;
use App\Shared\Persistence\PersistenceException;

interface UserPersisterInterface
{
    /**
     * @throws PersistenceException
     */
    public function save(User $user): void;
}
