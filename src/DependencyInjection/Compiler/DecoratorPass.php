<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * (c) Nikolay Nikolaev <evrinoma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evrinoma\CertBundle\DependencyInjection\Compiler;

use Evrinoma\CertBundle\EvrinomaCertBundle;
use Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DecoratorPass extends AbstractRecursivePass
{
    private array $services = ['cert'];

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($this->services as $alias) {
            $this->wireDecorates($container, $alias);
        }
    }

    private function wireDecorates(ContainerBuilder $container, string $name)
    {
        $decoratorQuery = $container->hasParameter('evrinoma.'.EvrinomaCertBundle::BUNDLE.'.'.$name.'.decorates.query');
        if ($decoratorQuery) {
            $decoratorQuery = $container->getParameter('evrinoma.'.EvrinomaCertBundle::BUNDLE.'.'.$name.'.decorates.query');
            $queryMediator = $container->getDefinition($decoratorQuery);
            $repository = $container->getDefinition('evrinoma.'.EvrinomaCertBundle::BUNDLE.'.'.$name.'.repository');
            $repository->setArgument(2, $queryMediator);
        }
        $decoratorCommand = $container->hasParameter('evrinoma.'.EvrinomaCertBundle::BUNDLE.'.'.$name.'.decorates.command');
        if ($decoratorCommand) {
            $decoratorCommand = $container->getParameter('evrinoma.'.EvrinomaCertBundle::BUNDLE.'.'.$name.'.decorates.command');
            $commandMediator = $container->getDefinition($decoratorCommand);
            $commandManager = $container->getDefinition('evrinoma.'.EvrinomaCertBundle::BUNDLE.'.'.$name.'.command.manager');
            $commandManager->setArgument(3, $commandMediator);
        }
    }
}
