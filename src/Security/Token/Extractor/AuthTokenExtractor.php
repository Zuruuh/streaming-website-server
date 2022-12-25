<?php

declare(strict_types=1);

namespace App\Security\Token\Extractor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\AccessToken\AccessTokenExtractorInterface;

final readonly class AuthTokenExtractor implements AccessTokenExtractorInterface
{
    public const SESSION_COOKIE = 'SID';

    public function extractAccessToken(Request $request): ?string
    {
        $token = (string) $request->cookies->get(self::SESSION_COOKIE);

        return strlen($token) > 0 ? $token : null;
    }
}
