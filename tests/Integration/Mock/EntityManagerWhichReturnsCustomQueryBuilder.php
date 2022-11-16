<?php

declare(strict_types=1);

namespace App\Tests\Integration\Mock;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

final class EntityManagerWhichReturnsCustomQueryBuilder extends EntityManagerDecorator implements EntityManagerInterface
{
    public function __construct(EntityManagerInterface $wrapped, private readonly QueryBuilder $queryBuilder)
    {
        parent::__construct($wrapped);
    }

    public function createQueryBuilder(): QueryBuilder
    {
        return clone $this->queryBuilder;
    }
}
