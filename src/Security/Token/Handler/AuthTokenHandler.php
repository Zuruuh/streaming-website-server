<?php

declare(strict_types=1);

namespace App\Security\Token\Handler;

use InvalidArgumentException;
use Predis\Client as Redis;
use Psr\Log\LoggerInterface;
use SensitiveParameter;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

final readonly class AuthTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private Redis $redis,
        private LoggerInterface $logger,
    ) {}

    public function getUserBadgeFrom(#[SensitiveParameter] string $accessToken): UserBadge
    {
        if (strlen($accessToken) === 0) {
            throw new BadCredentialsException('auth.not_authenticated');
        }

        $userId = (string) $this->redis->get($accessToken);

        if (strlen($userId) === 0) {
            throw new BadCredentialsException('auth.invalid_credentials');
        }

        try {
            Ulid::fromRfc4122($userId);
        } catch (InvalidArgumentException) {
            $this->logger->critical("User id \"$userId\" retrieved from redis is invalid");

            throw new BadCredentialsException('auth.invalid_credentials');
        }

        return new UserBadge($userId);
    }
}
