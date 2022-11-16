<?php

declare(strict_types=1);

namespace App\Contracts\User\Persistence;

use App\Entity\User;
use App\Shared\Persistence\PersistenceException;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

final class UserPersister implements UserPersisterInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function save(User $user): void
    {
        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (Throwable $e) {
            throw new PersistenceException($e->getMessage(), $e->getCode(), previous: $e);
        }
    }
}
