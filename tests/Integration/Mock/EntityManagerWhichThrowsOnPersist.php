<?php

declare(strict_types=1);

namespace App\Tests\Integration\Mock;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

final class EntityManagerWhichThrowsOnPersist extends EntityManagerDecorator implements EntityManagerInterface
{
    /**
     * @throws ORMException
     */
    public function persist(object $object): void
    {
        throw new ORMException();
    }
}
