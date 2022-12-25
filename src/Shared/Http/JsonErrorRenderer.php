<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

final readonly class JsonErrorRenderer implements ControllerInterface
{
    public function __construct(
        private string $kernelEnv,
    ) {}

    public function __invoke(Throwable $exception): Response
    {
        if ($exception instanceof HttpExceptionInterface) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
                'code' => $exception->getStatusCode(),
            ], $exception->getStatusCode());
        }

        return new JsonResponse([
            'message' => $this->kernelEnv !== 'dev' ? 'Internal Server Error' : $exception->getMessage(),
            'code' => 500,
        ], 500);
    }
}
