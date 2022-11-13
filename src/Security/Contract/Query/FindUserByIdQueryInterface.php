<?php

declare(strict_types=1);

namespace App\Security\Contract\Query;

use App\Entity\User;

interface FindUserByIdQueryInterface
{
    public function __invoke(string $id): ?User;
}
