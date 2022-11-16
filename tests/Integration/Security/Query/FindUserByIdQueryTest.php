<?php

declare(strict_types=1);

namespace App\Tests\Integration\Security\Query;

use App\Contracts\User\Persistence\UserPersister;
use App\Contracts\User\Query\CheckUserWithUsernameExistsQuery;
use App\Entity\User;
use App\Security\Contract\Query\FindUserByIdQuery;
use App\Tests\Integration\Entity\User\Fixtures\UserFactory;
use App\Tests\Integration\Mock\EntityManagerWhichReturnsCustomQueryBuilder;
use App\Tests\Integration\Mock\QueryBuilderWhichReturnsCustomQuery;
use App\Tests\Integration\Mock\QueryWhichThrowsOnGetResults;
use App\Tests\TestCase\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Ulid;

final class FindUserByIdQueryTest extends KernelTestCase
{
    public function testFindNothing(): void
    {
        $query = $this->inject(FindUserByIdQuery::class);
        self::assertNull($query(new Ulid()));
    }

    public function testFindExistingUser(): void
    {
        $user = UserFactory::createValidUser();
        $userPersister = $this->inject(UserPersister::class);
        $userPersister->save($user);

        $query = $this->inject(FindUserByIdQuery::class);
        $foundUser = $query($user->getId());

        self::assertInstanceOf(User::class, $foundUser);
        self::assertEquals($user->getId(), $foundUser->getId());
    }

    public function testWillFindMultipleResults(): void
    {
        $query = $this->getQueryWhichWillThrowOnGetResult();

        self::assertNull($query(new Ulid()));
    }

    private function getQueryWhichWillThrowOnGetResult(): FindUserByIdQuery
    {
        $em = $this->inject(EntityManagerInterface::class);
        $query = new QueryWhichThrowsOnGetResults($em, new NonUniqueResultException());
        $qb = new QueryBuilderWhichReturnsCustomQuery($em, $query);
        $em = new EntityManagerWhichReturnsCustomQueryBuilder($em, $qb);

        return new FindUserByIdQuery($em, $this->inject(LoggerInterface::class));
    }
}
