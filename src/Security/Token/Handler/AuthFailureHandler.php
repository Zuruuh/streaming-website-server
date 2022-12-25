<?php

declare(strict_types=1);

namespace App\Security\Token\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

final readonly class AuthFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
            'code' => Response::HTTP_UNAUTHORIZED,
        ], Response::HTTP_UNAUTHORIZED);
    }
}
