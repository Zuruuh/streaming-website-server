<?php

declare(strict_types=1);

namespace App\Shared\Transformer;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\VarDumper\VarDumper;
use Throwable;
use function Symfony\Component\String\u;

/**
 * @template T of object
 * @implements DataTransformerInterface<T, array<string, mixed>>
 */
final class FormToDTOTransformer implements DataTransformerInterface
{
    /**
     * @param class-string<T>       $dtoClass
     * @param array<string, string> $propertyPathMap
     */
    public function __construct(
        private readonly string $dtoClass,
        private readonly array  $propertyPathMap = [],
    ) {}

    /**
     * @param array<string, mixed>|null $value
     *
     * @throws ReflectionException
     */
    public function reverseTransform(mixed $value): ?object
    {
        if (!is_array($value)) {
            return null;
        }

        $parameters = [];

        $class = (new ReflectionClass($this->dtoClass));
        $constructor = $class->getConstructor();

        if (!($constructor instanceof ReflectionMethod)) {
            return new ($this->dtoClass)();
        }

        foreach ($constructor->getParameters() as $parameter) {
            $parameterName = u($parameter->getName())->snake()->toString();

            if (!array_key_exists($parameterName, $value)) {
                if (array_key_exists($parameterName, $this->propertyPathMap)) {
                    $parameters[$parameter->getPosition()] = $value[$this->propertyPathMap[$parameterName]];
                    continue;
                }

                if ($parameter->allowsNull()) {
                    $parameters[$parameter->getPosition()] = null;
                    continue;
                }

                return null;
            }

            $parameters[$parameter->getPosition()] = $value[$parameterName];
        }

        return $class->newInstanceArgs($parameters);
    }

    /**
     * @param T|null $value
     *
     * @return array<string, mixed>|null
     */
    public function transform(mixed $value): ?array
    {
        return null;
    }
}
