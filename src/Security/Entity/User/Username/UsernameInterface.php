<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Username;

use Stringable;

interface UsernameInterface extends Stringable
{
    public const MIN_LENGTH = 3;
    public const MAX_LENGTH = 24;
    public const PATTERN    = '/^[A-Za-z0-9][A-Za-z0-9\-_]+/';

    public const REQUIRED_MESSAGE      = 'validators.user.username.required';
    public const LENGTH_MESSAGE        = 'validators.user.username.length';
    public const PATTERN_MESSAGE       = 'validators.user.username.pattern';
    public const ALREADY_TAKEN_MESSAGE = 'validators.user.username.already_taken';
    public const NOT_IN_USE_MESSAGE    = 'validators.user.username.not_in_use';
}
