<?php

declare(strict_types=1);

namespace App\Security\Contract;

use App\Entity\User;

interface UserFinderByIdQueryInterface
{
    public function __invoke(string $id): ?User;
}
