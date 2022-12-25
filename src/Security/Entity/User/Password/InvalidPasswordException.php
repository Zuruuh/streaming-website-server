<?php

declare(strict_types=1);

namespace App\Security\Entity\User\Password;

use Exception;

final class InvalidPasswordException extends Exception
{
    public function __construct()
    {
        parent::__construct('validators.user.password.invalid_password');
    }
}
