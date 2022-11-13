<?php

declare(strict_types=1);

namespace App\Security\Contract\Query;

use App\Entity\User;
use App\Shared\Query\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;

final class FindFindUserByIdQuery extends AbstractQuery implements FindUserByIdQueryInterface
{
    public function __invoke(string $id): ?User
    {
        $query = $this
            ->em
            ->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.id = :id')
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
