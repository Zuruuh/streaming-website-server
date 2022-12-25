<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class HttpJsonSerializer
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    public function serialize(object $data, int $httpStatus = 200): JsonResponse
    {
        return new JsonResponse($this->serializer->serialize($data, 'json'), $httpStatus, json: true);
    }
}
