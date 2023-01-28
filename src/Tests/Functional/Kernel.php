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

namespace Evrinoma\CertBundle\Tests\Functional;

use Evrinoma\CertBundle\EvrinomaCertBundle;
use Evrinoma\DtoBundle\EvrinomaDtoBundle;
use Evrinoma\TestUtilsBundle\Kernel\AbstractApiKernel;

/**
 * Kernel.
 */
class Kernel extends AbstractApiKernel
{
    protected string $bundlePrefix = 'CertBundle';
    protected string $rootDir = __DIR__;

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array_merge(
            parent::registerBundles(), [
                new EvrinomaDtoBundle(),
                new EvrinomaCertBundle(),
            ]
        );
    }

    protected function getBundleConfig(): array
    {
        return ['framework.yaml', 'jms_serializer.yaml'];
    }
}
