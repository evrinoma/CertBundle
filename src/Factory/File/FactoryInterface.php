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

namespace Evrinoma\CertBundle\Factory\File;

use Evrinoma\CertBundle\Dto\FileApiDtoInterface;
use Evrinoma\CertBundle\Model\File\FileInterface;

interface FactoryInterface
{
    /**
     * @param FileApiDtoInterface $dto
     *
     * @return FileInterface
     */
    public function create(FileApiDtoInterface $dto): FileInterface;
}
