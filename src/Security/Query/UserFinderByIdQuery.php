<?php

declare(strict_types=1);

namespace App\Security\Query;

use App\Entity\User;
use App\Security\Contract\UserFinderByIdQueryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Psr\Log\LoggerInterface;

final class UserFinderByIdQuery implements UserFinderByIdQueryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(string $id): ?User
    {
        $query = $this
            ->em
            ->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
        ;

        try {
            $user = $query->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $this->logger->critical(
                "Non unique result for unique identifier query! \"{$e->getMessage()}\"",
                ['e' => $e]
            );

            return null;
        }

        return $user instanceof User ? $user : null;
    }
}
