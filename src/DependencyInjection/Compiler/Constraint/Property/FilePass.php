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

use Evrinoma\CertBundle\Validator\FileValidator;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractConstraint;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class FilePass extends AbstractConstraint implements CompilerPassInterface
{
    public const FILE_CONSTRAINT = 'evrinoma.cert.constraint.property.file';

    protected static string $alias = self::FILE_CONSTRAINT;
    protected static string $class = FileValidator::class;
    protected static string $methodCall = 'addPropertyConstraint';
}
