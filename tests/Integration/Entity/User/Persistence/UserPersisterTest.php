<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity\User\Persistence;

use App\Contracts\User\Persistence\UserPersister;
use App\Entity\User;
use App\Security\Contract\Query\FindUserByIdQuery;
use App\Shared\Persistence\PersistenceException;
use App\Tests\Integration\Entity\User\Fixtures\UserFactory;
use App\Tests\Integration\Mock\EntityManagerWhichThrowsOnPersist;
use App\Tests\TestCase\KernelTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

final class UserPersisterTest extends KernelTestCase
{
    private UserPersister $userPersister;
    private FindUserByIdQuery $findUserByIdQuery;

    public function setUp(): void
    {
        self::bootKernel();
        $this->userPersister = $this->inject(UserPersister::class);
        $this->findUserByIdQuery = $this->inject(FindUserByIdQuery::class);
    }

    public function testPersistenceIsSuccessful(): void
    {
        $user = UserFactory::createValidUser();
        $this->userPersister->save($user);
        self::assertTrue(true);
    }

    public function testUserPersistenceActuallySavesToDB(): void
    {
        $user = UserFactory::createValidUser();
        $this->userPersister->save($user);

        $foundUser = $this->findUserByIdQuery->__invoke($user->getId());
        self::assertInstanceOf(User::class, $foundUser);
        self::assertEquals($user->getId()->toBase32(), $foundUser->getId()->toBase32());
    }

    public function testPersistenceExceptionIsThrown(): void
    {
        $em = $this->createThrowingEntityManager();
        $persister = new UserPersister($em);

        $user = UserFactory::createValidUser();
        try {
            $persister->save($user);
        } catch (PersistenceException) {
            self::assertTrue(true);
        }
    }

    private function createThrowingEntityManager(): EntityManagerInterface
    {
        $em = $this->inject(EntityManagerInterface::class);

        return new EntityManagerWhichThrowsOnPersist($em);
    }
}
