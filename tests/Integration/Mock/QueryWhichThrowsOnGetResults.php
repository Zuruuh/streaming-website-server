<?php

declare(strict_types=1);

namespace App\Tests\Integration\Mock;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

final class QueryWhichThrowsOnGetResults extends AbstractQuery
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly ORMException $exception = new ORMException()
    ) {
        parent::__construct($em);
    }

    public function getSQL(): string
    {
        return '';
    }

    protected function _doExecute(): int
    {
        return 0;
    }

    /**
     * @throws ORMException
     */
    public function getResult($hydrationMode = self::HYDRATE_OBJECT): mixed
    {
        throw $this->exception;
    }

    /**
     * @throws ORMException
     */
    public function getSingleResult($hydrationMode = null): mixed
    {
        throw $this->exception;
    }

    /**
     * @throws ORMException
     */
    public function getOneOrNullResult($hydrationMode = null): mixed
    {
        throw $this->exception;
    }
}
