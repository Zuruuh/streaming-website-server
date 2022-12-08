<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Password;

use Stringable;

interface PasswordInterface extends Stringable
{
    public const MIN_LENGTH = 8;
    public const MAX_LENGTH = 64;
}
