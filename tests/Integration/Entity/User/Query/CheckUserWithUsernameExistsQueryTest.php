<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity\User\Query;

use App\Contracts\User\Persistence\UserPersister;
use App\Contracts\User\Query\CheckUserWithUsernameExistsQuery;
use App\Entity\User\Username;
use App\Tests\Integration\Entity\User\Fixtures\CheckUserWithUsernameExistsQueryMock;
use App\Tests\Integration\Entity\User\Fixtures\UserFactory;
use App\Tests\Integration\Mock\EntityManagerWhichReturnsCustomQueryBuilder;
use App\Tests\Integration\Mock\QueryBuilderWhichReturnsCustomQuery;
use App\Tests\Integration\Mock\QueryWhichThrowsOnGetResults;
use App\Tests\TestCase\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Psr\Log\LoggerInterface;

final class CheckUserWithUsernameExistsQueryTest extends KernelTestCase
{
    public function testWithUsernameAlreadyTaken(): void
    {
        $username = $this->getUsername();
        $userPersister = $this->inject(UserPersister::class);

        $user = UserFactory::createValidUser(username: $username);
        $userPersister->save($user);

        $query = $this->inject(CheckUserWithUsernameExistsQuery::class);
        self::assertTrue($query($username));
    }

    public function testWithUsernameNotAlreadyTaken(): void
    {
        $username = $this->getUsername();
        $query = $this->inject(CheckUserWithUsernameExistsQuery::class);

        self::assertFalse($query($username));
    }

    public function testFailedQueryReturnsTrue(): void
    {
        $query = $this->getQueryWhichWillThrowOnGetResults();

        self::assertTrue($query($this->getUsername()));
    }

    private function getQueryWhichWillThrowOnGetResults(): CheckUserWithUsernameExistsQuery
    {
        $em = $this->inject(EntityManagerInterface::class);
        $query = new QueryWhichThrowsOnGetResults($em, new NoResultException());
        $qb = new QueryBuilderWhichReturnsCustomQuery($em, $query);
        $em = new EntityManagerWhichReturnsCustomQueryBuilder($em, $qb);

        return new CheckUserWithUsernameExistsQuery($em, $this->inject(LoggerInterface::class));
    }

    private function getUsername(): Username
    {
        return new Username('username', new CheckUserWithUsernameExistsQueryMock());
    }
}
