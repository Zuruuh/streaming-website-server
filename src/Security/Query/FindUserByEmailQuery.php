<?php

declare(strict_types=1);

namespace App\Security\Query;

use App\Security\Entity\User;
use App\Security\Entity\User\Contract\Query\FindUserByEmailQueryInterface;
use App\Security\Entity\User\Email\EmailInterface;
use App\Shared\Query\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;

final readonly class FindUserByEmailQuery extends AbstractQuery implements FindUserByEmailQueryInterface
{
    public function __invoke(EmailInterface $email): ?User
    {
        try {
            $user = $this
                ->em
                ->createQueryBuilder()
                ->select('user')
                ->from(User::class, 'user')
                ->where('user.email.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->setResultCacheLifetime(-1)
                ->setResultCacheId(self::getQueryCacheId($email))
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $this->logger->critical($e->getMessage());

            return null;
        }

        if (!($user instanceof User)) {
            return null;
        }

        return $user;
    }

    public static function getQueryCacheId(EmailInterface $email): string
    {
        return self::class . "#{$email->__toString()}";
    }
}
