<?php

declare(strict_types=1);

namespace App\Security\Contract\Query;

use App\Entity\User;
use App\Shared\Query\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Uid\Ulid;

final class FindUserByIdQuery extends AbstractQuery implements FindUserByIdQueryInterface
{
    public function __invoke(Ulid $id): ?User
    {
        $query = $this
            ->em
            ->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.id = :id')
            ->setParameter('id', $id, 'ulid')
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
