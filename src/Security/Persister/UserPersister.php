<?php

declare(strict_types=1);

namespace App\Security\Persister;

use App\Security\Entity\User;
use App\Security\Entity\User\Contract\Persister\UserPersisterInterface;
use App\Security\Entity\User\Contract\Query\FindUserByEmailQueryInterface;
use App\Security\Entity\User\Contract\Query\FindUserByUsernameQueryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

final readonly class UserPersister implements UserPersisterInterface
{
    public function __construct(
        private EntityManagerInterface           $em,
        private CacheItemPoolInterface           $doctrineResultCachePool,
        private FindUserByUsernameQueryInterface $findUserByUsernameQuery,
        private FindUserByEmailQueryInterface    $findUserByEmailQuery,
    ) {}

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();

        $this->clearCacheEntry($user);
    }

    private function clearCacheEntry(User $user): void
    {
        $queryCacheIds = [
            $this->findUserByUsernameQuery::getQueryCacheId($user->getUsername()),
            $this->findUserByEmailQuery::getQueryCacheId($user->getEmail()),
        ];

        foreach ($queryCacheIds as $queryCacheId) {
            $this->doctrineResultCachePool->deleteItem($queryCacheId);
        }
    }
}
