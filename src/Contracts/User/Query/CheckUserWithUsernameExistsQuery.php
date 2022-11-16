<?php

declare(strict_types=1);

namespace App\Contracts\User\Query;

use App\Entity\User;
use App\Entity\User\Username;
use App\Shared\Query\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\VarDumper\VarDumper;

final class CheckUserWithUsernameExistsQuery extends AbstractQuery implements CheckUserWithUsernameExistsQueryInterface
{
    public function __invoke(Username $username): bool
    {
        $query = $this
            ->em
            ->createQueryBuilder()
            ->select('COUNT(user.id)')
            ->from(User::class, 'user')
            ->where('user.username.username = :username')
            ->setParameter('username', $username->__toString())
            ->getQuery()
        ;

        try {
            $count = $query->getSingleScalarResult();
            $count = is_numeric($count) ? (int) $count : 0;
        } catch (NoResultException|NonUniqueResultException) {
            return true;
        }

        return (bool) $count;
    }
}
