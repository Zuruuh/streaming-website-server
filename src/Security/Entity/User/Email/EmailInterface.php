<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Email;

use Stringable;

interface EmailInterface extends Stringable
{
    public const REQUIRED_MESSAGE = 'validators.user.email.required';
    public const INVALID_MESSAGE = 'validators.user.email.invalid';
    public const ALREADY_IN_USE_MESSAGE = 'validators.user.email.already_in_use';
    public const NOT_IN_USE_MESSAGE = 'validators.user.email.not_in_use';
}
