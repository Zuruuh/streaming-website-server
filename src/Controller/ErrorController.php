<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

#[AsController]
final class ErrorController
{
    public function __construct(
        private readonly string $env,
    ) {}

    public function __invoke(Throwable $exception): Response
    {
        if (in_array($this->env, ['staging', 'prod'], true)) {
            return new JsonResponse([
                'message' => 'Internal Server Error',
                'code' => 500,
            ], 500);
        }

        $statusCode = $exception instanceof HttpException ? $exception->getStatusCode() : ($exception->getCode() ?: 500);
        $payload = [
            'message' => $exception->getMessage(),
            'code' => $statusCode,
        ];

        return new JsonResponse($payload, (int) $statusCode);
    }
}
