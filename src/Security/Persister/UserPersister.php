<?php

declare(strict_types=1);

namespace App\Security\Persister;

use App\Security\Entity\User;
use App\Security\Entity\User\Contract\Persister\UserPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UserPersister implements UserPersisterInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}
