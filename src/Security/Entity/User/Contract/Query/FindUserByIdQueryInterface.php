<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Contract\Query;

use App\Security\Entity\User;
use Symfony\Component\Uid\Ulid;

interface FindUserByIdQueryInterface
{
    public function __invoke(Ulid $id): ?User;

    public static function getQueryCacheId(Ulid $id): string;
}
