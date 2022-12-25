<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Contract\Query;

use App\Security\Entity\User;
use App\Security\Entity\User\Username\UsernameInterface;

interface FindUserByUsernameQueryInterface
{
    public function __invoke(UsernameInterface $username): ?User;

    public static function getQueryCacheId(UsernameInterface $username): string;
}
