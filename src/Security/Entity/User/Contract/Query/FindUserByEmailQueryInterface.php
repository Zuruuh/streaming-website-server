<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Contract\Query;

use App\Security\Entity\User;
use App\Security\Entity\User\Email\EmailInterface;

interface FindUserByEmailQueryInterface
{
    public function __invoke(EmailInterface $email): ?User;

    public static function getQueryCacheId(EmailInterface $email): string;
}
