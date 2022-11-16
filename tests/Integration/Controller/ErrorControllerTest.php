<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\ErrorController;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;


final class ErrorControllerTest extends TestCase
{
    public function testThatNoInformationIsGivenInProd(): void
    {
        $controller = $this->getErrorController('prod');
        $content = $this->submitFakeException($controller);

        self::assertArrayHasKey('message', $content);
        self::assertEquals('Internal Server Error', $content['message']);
    }

    public function testThatExceptionAreCorrectlyDisplayedInDevEnv(): void
    {
        $content = $this->submitFakeException(throwable: new Exception('I am a teapot', 418));

        self::assertArrayHasKey('message', $content);
        self::assertArrayHasKey('code', $content);
        self::assertEquals('I am a teapot', $content['message']);
        self::assertEquals(418, $content['code']);
    }

    public function testThatHttpExceptionAreCorrectlyDisplayed(): void
    {
        $content = $this->submitFakeException(throwable: new HttpException(418, 'I am a teapot'));

        self::assertArrayHasKey('message', $content);
        self::assertArrayHasKey('code', $content);
        self::assertEquals('I am a teapot', $content['message']);
        self::assertEquals(418, $content['code']);
    }

    /**
     * @return array<string, string|int>
     */
    private function submitFakeException(ErrorController $controller = null, Throwable $throwable = new Exception()): array
    {
        $controller ??= $this->getErrorController();
        $response = $controller($throwable);
        $content = $response->getContent() ?: '{}';
        $content = json_decode($content, true);

        self::assertIsArray($content);

        return $content;
    }

    private function getErrorController(string $env = 'dev'): ErrorController
    {
        return new ErrorController($env);
    }
}
