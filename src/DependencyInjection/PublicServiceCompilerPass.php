<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Shared\Service\ServiceInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PublicServiceCompilerPass implements CompilerPassInterface
{
    private const FORCE_PUBLIC_SERVICES = [
        'doctrine.orm.entity_manager',
    ];

    public function process(ContainerBuilder $container): void
    {
        /**
         * @var class-string $id
         */
        foreach ($container->getDefinitions() as $id => $definition) {
            if (is_subclass_of($id, ServiceInterface::class)) {
                $definition->setPublic(true);
            }
        }

        foreach (self::FORCE_PUBLIC_SERVICES as $service) {
            $container->getAlias($service)->setPublic(true);
        }
    }
}
