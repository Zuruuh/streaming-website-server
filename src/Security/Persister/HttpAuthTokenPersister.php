<?php

declare(strict_types=1);

namespace App\Security\Persister;

use App\Security\Token\Extractor\AuthTokenExtractor;
use App\Security\Token\Model\AuthToken;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

final readonly class HttpAuthTokenPersister
{
    public function __invoke(AuthToken $authToken, Response $response): Response
    {
        $cookie = new Cookie(
            name: AuthTokenExtractor::SESSION_COOKIE,
            value: $authToken->__toString(),
            secure: true,
            httpOnly: true,
            sameSite: Cookie::SAMESITE_STRICT,
        );

        $response->headers->setCookie($cookie);

        return $response;
    }
}
