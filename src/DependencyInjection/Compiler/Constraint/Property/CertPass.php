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

namespace Evrinoma\CertBundle\DependencyInjection\Compiler\Constraint\Property;

use Evrinoma\CertBundle\Validator\CertValidator;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractConstraint;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class CertPass extends AbstractConstraint implements CompilerPassInterface
{
    public const CERT_CONSTRAINT = 'evrinoma.cert.constraint.property.cert';

    protected static string $alias = self::CERT_CONSTRAINT;
    protected static string $class = CertValidator::class;
    protected static string $methodCall = 'addPropertyConstraint';
}
