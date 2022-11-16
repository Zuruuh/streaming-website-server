<?php

declare(strict_types=1);

namespace App\Security\Contract\Query;

use App\Entity\User;
use App\Shared\Service\ServiceInterface;
use Symfony\Component\Uid\Ulid;

interface FindUserByIdQueryInterface extends ServiceInterface
{
    public function __invoke(Ulid $id): ?User;
}
