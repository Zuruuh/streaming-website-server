<?php

declare(strict_types=1);

namespace App\Contracts\User\Persistence;

use App\Entity\User;
use App\Shared\Persistence\PersistenceException;
use App\Shared\Service\ServiceInterface;

interface UserPersisterInterface extends ServiceInterface
{
    /**
     * @throws PersistenceException
     */
    public function save(User $user): void;
}
