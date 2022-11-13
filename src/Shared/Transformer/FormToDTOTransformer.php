<?php

declare(strict_types=1);

namespace App\Shared\Transformer;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Form\DataTransformerInterface;
use Throwable;
use function Symfony\Component\String\u;

/**
 * @template T of object
 * @implements DataTransformerInterface<T, array<string, mixed>>
 */
final class FormToDTOTransformer implements DataTransformerInterface
{
    /**
     * @param class-string<T>            $dtoClass
     * @param array<string, string>      $propertiesMap
     */
    public function __construct(
        private readonly string $dtoClass,
        private readonly array  $propertiesMap = [],
    ) {}

    /**
     * @param array<string, mixed>|null $value
     */
    public function reverseTransform(mixed $value): ?object
    {
        try {
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
                    if (array_key_exists($parameterName, $this->propertiesMap)) {
                        $parameters[$parameter->getPosition()] = $value[$this->propertiesMap[$parameterName]];
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

            try {
                return $class->newInstanceArgs($parameters);
            } catch (Throwable) {
                return null;
            }
        } catch (ReflectionException) {
            return null;
        }
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
