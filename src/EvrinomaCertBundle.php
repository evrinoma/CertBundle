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

namespace Evrinoma\CertBundle;

use Evrinoma\CertBundle\DependencyInjection\Compiler\Constraint\Complex\CertPass;
use Evrinoma\CertBundle\DependencyInjection\Compiler\Constraint\Property\CertPass as PropertyCertPass;
use Evrinoma\CertBundle\DependencyInjection\Compiler\Constraint\Property\FilePass as PropertyFilePass;
use Evrinoma\CertBundle\DependencyInjection\Compiler\DecoratorPass;
use Evrinoma\CertBundle\DependencyInjection\Compiler\MapEntityPass;
use Evrinoma\CertBundle\DependencyInjection\Compiler\ServicePass;
use Evrinoma\CertBundle\DependencyInjection\EvrinomaCertExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EvrinomaCertBundle extends Bundle
{
    public const BUNDLE = 'cert';

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container
            ->addCompilerPass(new MapEntityPass($this->getNamespace(), $this->getPath()))
            ->addCompilerPass(new PropertyCertPass())
            ->addCompilerPass(new PropertyFilePass())
            ->addCompilerPass(new CertPass())
            ->addCompilerPass(new DecoratorPass())
            ->addCompilerPass(new ServicePass())
        ;
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new EvrinomaCertExtension();
        }

        return $this->extension;
    }
}
