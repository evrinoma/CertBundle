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

namespace Evrinoma\CertBundle\Mediator\File;

use Evrinoma\CertBundle\Dto\FileApiDtoInterface;
use Evrinoma\CertBundle\Exception\File\FileCannotBeCreatedException;
use Evrinoma\CertBundle\Exception\File\FileCannotBeRemovedException;
use Evrinoma\CertBundle\Exception\File\FileCannotBeSavedException;
use Evrinoma\CertBundle\Model\File\FileInterface;

interface CommandMediatorInterface
{
    /**
     * @param FileApiDtoInterface $dto
     * @param FileInterface       $entity
     *
     * @return FileInterface
     *
     * @throws FileCannotBeSavedException
     */
    public function onUpdate(FileApiDtoInterface $dto, FileInterface $entity): FileInterface;

    /**
     * @param FileApiDtoInterface $dto
     * @param FileInterface       $entity
     *
     * @throws FileCannotBeRemovedException
     */
    public function onDelete(FileApiDtoInterface $dto, FileInterface $entity): void;

    /**
     * @param FileApiDtoInterface $dto
     * @param FileInterface       $entity
     *
     * @return FileInterface
     *
     * @throws FileCannotBeSavedException
     * @throws FileCannotBeCreatedException
     */
    public function onCreate(FileApiDtoInterface $dto, FileInterface $entity): FileInterface;
}
