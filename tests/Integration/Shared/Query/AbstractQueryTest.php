<?php

declare(strict_types=1);

namespace App\Tests\Integration\Shared\Query;

use App\Shared\Query\AbstractQuery;
use App\Tests\TestCase\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

final class AbstractQueryTest extends KernelTestCase
{
    public function testInstantiation(): void
    {
        $query = new class(
            $this->inject(EntityManagerInterface::class),
            $this->inject(LoggerInterface::class),
        ) extends AbstractQuery {};

        self::assertTrue(true);
    }
}
