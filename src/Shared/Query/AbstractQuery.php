<?php

declare(strict_types=1);

namespace App\Shared\Query;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

abstract readonly class AbstractQuery
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected LoggerInterface $logger,
    ) {}
}
