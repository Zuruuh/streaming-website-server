<?php

declare(strict_types=1);

namespace App\Security\Query;

use App\Security\Entity\User;
use App\Security\Entity\User\Contract\Query\FindUserByIdQueryInterface;
use App\Shared\Query\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

final readonly class FindUserByIdQuery extends AbstractQuery implements FindUserByIdQueryInterface
{
    public function __invoke(Ulid $id): ?User
    {
        try {
            $user = $this
                ->em
                ->createQueryBuilder()
                ->select('user')
                ->from(User::class, 'user')
                ->where('user.ulid = :id')
                ->setParameter('id', $id, UlidType::NAME)
                ->getQuery()
                ->setResultCacheLifetime(-1)
                ->setResultCacheId(self::getQueryCacheId($id))
                ->getOneOrNullResult()
            ;
        } catch (NonUniqueResultException $e) {
            $this->logger->critical($e->getMessage());

            return null;
        }

        if (!($user instanceof User)) {
            return null;
        }

        return $user;
    }

    public static function getQueryCacheId(Ulid $id): string
    {
        return self::class . "#{$id->toRfc4122()}";
    }
}
