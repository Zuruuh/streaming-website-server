<?php

declare(strict_types=1);

namespace App\Tests\TestCase;

use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;

abstract class KernelTestCase extends BaseKernelTestCase
{
    /**
     * @template T of object
     * @param class-string<T> $class
     *
     * @return T
     */
    protected static function inject(string $class): object
    {
        if (!self::$booted) {
            self::bootKernel();
        }

        /**
         * @var T $service
         */
        $service = self::getContainer()->get($class);
        if (class_exists($class) && !($service instanceof $class)) {
            throw new Exception("Service with id $class should be an instance of the class with the same name");
        }

        return $service;
    }
}
