<?php

declare(strict_types=1);

namespace App\Security\Token\Persister;

use App\Security\Token\Model\AuthToken;
use App\Security\Token\Persister\Exception\AuthTokenPersistenceException;

interface AuthTokenPersisterInterface
{
    /**
     * @throws AuthTokenPersistenceException
     */
    public function __invoke(AuthToken $authToken): void;
}
