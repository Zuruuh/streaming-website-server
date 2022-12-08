<?php

declare(strict_types=1);

namespace App\Security\Query;

use App\Security\Entity\User;
use App\Security\Entity\User\Contract\Query\FindUserByUsernameQueryInterface;
use App\Security\Entity\User\Username\UsernameInterface;
use App\Shared\Query\AbstractQuery;
use Doctrine\ORM\AbstractQuery as DoctrineAbstractQuery;
use Doctrine\ORM\NonUniqueResultException;

final class FindUserByUsernameQuery extends AbstractQuery implements FindUserByUsernameQueryInterface
{
    public function __invoke(UsernameInterface $username): ?User
    {
        try {
            $user = $this
                ->em
                ->createQueryBuilder()
                ->select('user')
                ->from(User::class, 'user')
                ->where('user.username.username = :username')
                ->setParameter('username', $username)
                ->getQuery()
                ->setResultCacheLifetime(-1)
                ->setResultCacheId(self::getQueryCacheId($username))
                ->getOneOrNullResult(DoctrineAbstractQuery::HYDRATE_OBJECT);
        } catch (NonUniqueResultException $e) {
            $this->logger->critical($e->getMessage());

            return null;
        }

        if (!($user instanceof User)) {
            return null;
        }

        return $user;
    }

    public static function getQueryCacheId(UsernameInterface $username): string
    {
        return self::class . ":{$username->__toString()}";
    }
}
