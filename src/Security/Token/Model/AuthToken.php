<?php

declare(strict_types=1);

namespace App\Security\Token\Model;

use Stringable;
use Symfony\Component\Uid\AbstractUid;

final readonly class AuthToken implements Stringable
{
    public function __construct(
        private AbstractUid $randomUid,
        public AbstractUid $userId,
    ) {}

    public function __toString(): string
    {
        return "{$this->randomUid->toRfc4122()}:{$this->userId->toRfc4122()}";
    }
}
