<?php

declare(strict_types=1);

namespace App\Shared\Form;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Form\DataTransformerInterface;
use Throwable;
use function Symfony\Component\String\u;

/**
 * @template T of object
 *
 * @implements DataTransformerInterface<mixed, mixed>
 */
final class ArrayToDTOTransformer implements DataTransformerInterface
{
    /**
     * @param class-string<T>       $dtoClass
     * @param array<string, string> $propertyPathMap
     */
    public function __construct(
        private readonly string $dtoClass,
        private readonly array $propertyPathMap = [],
    ) {}

    public function transform(mixed $value): mixed
    {
        return $value;
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (!is_array($value)) {
            return null;
        }

        $parameters = [];

        $class = new ReflectionClass($this->dtoClass);
        $constructor = $class->getConstructor();

        if (!($constructor instanceof ReflectionMethod)) {
            return new ($this->dtoClass)();
        }

        foreach ($constructor->getParameters() as $parameter) {
            $snakeCasedParameterName = u($parameter->getName())->snake()->toString();

            if (!array_key_exists($snakeCasedParameterName, $value)) {
                if (array_key_exists($parameter->getName(), $this->propertyPathMap)) {
                    $parameters[$parameter->getPosition()] = $value[$this->propertyPathMap[$parameter->getName()]];

                    continue;
                }

                if ($parameter->allowsNull()) {
                    $parameters[$parameter->getPosition()] = null;
                    continue;
                }

                throw new InvalidArgumentException(
                    "Could not find the form field for parameter named \"{$parameter->getName()}\" in $this->dtoClass's constructor!"
                );
            }

            $parameters[$parameter->getPosition()] = $value[$snakeCasedParameterName];
        }

        try {
            return $class->newInstanceArgs($parameters);
        } catch (Throwable $e) {
            return null;
        }
    }
}
