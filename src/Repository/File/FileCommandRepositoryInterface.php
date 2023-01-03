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

namespace Evrinoma\CertBundle\Repository\File;

use Evrinoma\CertBundle\Exception\File\FileCannotBeRemovedException;
use Evrinoma\CertBundle\Exception\File\FileCannotBeSavedException;
use Evrinoma\CertBundle\Model\File\FileInterface;

interface FileCommandRepositoryInterface
{
    /**
     * @param FileInterface $type
     *
     * @return bool
     *
     * @throws FileCannotBeSavedException
     */
    public function save(FileInterface $type): bool;

    /**
     * @param FileInterface $type
     *
     * @return bool
     *
     * @throws FileCannotBeRemovedException
     */
    public function remove(FileInterface $type): bool;
}
