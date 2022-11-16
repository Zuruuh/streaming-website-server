<?php

declare(strict_types=1);

namespace App\Tests\Integration\Mock;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

final class QueryBuilderWhichReturnsCustomQuery extends QueryBuilder
{
    public function __construct(EntityManagerInterface $em, private readonly AbstractQuery $query)
    {
        parent::__construct($em);
    }

    public function getQuery(): AbstractQuery
    {
        /**
         * @var Query $query
         */
        $query = clone $this->query;

        return $query;
    }
}
