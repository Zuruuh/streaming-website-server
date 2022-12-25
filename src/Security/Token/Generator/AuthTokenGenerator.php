<?php

declare(strict_types=1);

namespace App\Security\Token\Generator;

use App\Security\Entity\User;
use App\Security\Token\Model\AuthToken;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

final readonly class AuthTokenGenerator implements AuthTokenGeneratorInterface
{
    public function generate(AbstractUid $randomUid, AbstractUid $userUid): AuthToken
    {
        return new AuthToken($randomUid, $userUid);
    }

    public function forUser(User $user): AuthToken
    {
        return $this->generate(Uuid::v7(), $user->getId());
    }
}
