<?php

declare(strict_types=1);

namespace App\Shared\Transformer\Fixture;

final class DTOWithNullableProperty
{
    public function __construct(
        public readonly ?string $username,
    ) {}
}