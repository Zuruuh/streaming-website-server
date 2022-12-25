<?php

declare(strict_types=1);

namespace App\Security\Token\Persister;

use App\Security\Token\Model\AuthToken;
use Predis\Client as Redis;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class AuthTokenPersister implements AuthTokenPersisterInterface
{
    private const AUTH_TOKEN_TTL_IN_SECONDS = 60 * 60;

    private bool $debug;

    public function __construct(
        private Redis $redis,
        #[Autowire('kernel.debug')] bool $debug,
    ) {
        $this->debug = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(AuthToken $authToken): void
    {
        $this->redis->set(
            $authToken->__toString(),
            $authToken->userId->toRfc4122(),
            'EX',
            $this->debug ? self::AUTH_TOKEN_TTL_IN_SECONDS * 999 : self::AUTH_TOKEN_TTL_IN_SECONDS,
        );
    }
}
