<?php

declare(strict_types=1);

namespace App\Tests\Integration\Shared\Transformer;

use App\Shared\Transformer\Fixture\DTOWithNoConstructor;
use App\Shared\Transformer\Fixture\DTOWithNullableProperty;
use App\Shared\Transformer\FormToDTOTransformer;
use App\UseCase\Login\LoginInputDTO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\VarDumper\VarDumper;

final class FormToDTOTransformerTest extends TestCase
{
    public function testInvalidArrayPassedToTransformer(): void
    {
        $transformer = $this->getTransformer();

        self::assertNull($transformer->reverseTransform(null));
    }

    public function testDTOInstantiationWithNoConstructor(): void
    {
        $transformer = new FormToDTOTransformer(DTOWithNoConstructor::class);

        self::assertInstanceOf(DTOWithNoConstructor::class, $transformer->reverseTransform([]));
    }

    public function testValidDataToDTO(): void
    {
        $transformer = $this->getTransformer();

        $data = [
            'username' => 'username',
            'plain_password' => '12345678'
        ];

        $dto = $transformer->reverseTransform($data);
        self::assertInstanceOf(LoginInputDTO::class, $dto);
    }

    public function testValidDataToDTOWithCustomPropertyPathMap(): void
    {
        $transformer = $this->getTransformer(['plain_password' => 'password']);

        $data = [
            'username' => 'username',
            'password' => '12345678'
        ];

        $dto = $transformer->reverseTransform($data);
        self::assertInstanceOf(LoginInputDTO::class, $dto);
    }

    public function testValidDataToDTOWithNullablePropertyNotSpecified(): void
    {
        $transformer = new FormToDTOTransformer(DTOWithNullableProperty::class);

        $dto = $transformer->reverseTransform([]);
        self::assertInstanceOf(DTOWithNullableProperty::class, $dto);
        self::assertNull($dto->username);
    }

    public function testInvalidDataToDTO(): void
    {
        $transformer = $this->getTransformer();

        $data = [
            'plain_password' => '12345678'
        ];

        $dto = $transformer->reverseTransform($data);
        self::assertNull($dto);
    }

    public function testDefaultTransformReturnsNull(): void
    {
        $transformer = $this->getTransformer();

        self::assertNull($transformer->transform(null));
        self::assertNull($transformer->transform($this));
    }

    /**
     * @param array<string, string> $propertiesMap
     */
    private function getTransformer(array $propertiesMap = []): FormToDTOTransformer
    {
        return new FormToDTOTransformer(LoginInputDTO::class, $propertiesMap);
    }
}
