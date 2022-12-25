<?php

declare(strict_types=1);

namespace App\Security\Token\Generator;

use App\Security\Entity\User;
use App\Security\Token\Model\AuthToken;
use Symfony\Component\Uid\AbstractUid;

interface AuthTokenGeneratorInterface
{
    public function generate(AbstractUid $randomUid, AbstractUid $userUid): AuthToken;

    public function forUser(User $user): AuthToken;
}
