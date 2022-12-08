<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Username;

use Stringable;

interface UsernameInterface extends Stringable
{
    public const MIN_LENGTH = 3;
    public const MAX_LENGTH = 24;
    public const PATTERN = '/^[A-Za-z0-9][A-Za-z0-9\-_]+/';
}
