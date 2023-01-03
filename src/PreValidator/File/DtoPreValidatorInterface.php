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

namespace Evrinoma\CertBundle\PreValidator\File;

use Evrinoma\CertBundle\Dto\FileApiDtoInterface;
use Evrinoma\CertBundle\Exception\File\FileInvalidException;

interface DtoPreValidatorInterface
{
    /**
     * @param FileApiDtoInterface $dto
     *
     * @throws FileInvalidException
     */
    public function onPost(FileApiDtoInterface $dto): void;

    /**
     * @param FileApiDtoInterface $dto
     *
     * @throws FileInvalidException
     */
    public function onPut(FileApiDtoInterface $dto): void;

    /**
     * @param FileApiDtoInterface $dto
     *
     * @throws FileInvalidException
     */
    public function onDelete(FileApiDtoInterface $dto): void;
}
