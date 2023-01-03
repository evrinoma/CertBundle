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

namespace Evrinoma\CertBundle\Mediator\Cert;

use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Exception\Cert\CertCannotBeCreatedException;
use Evrinoma\CertBundle\Exception\Cert\CertCannotBeRemovedException;
use Evrinoma\CertBundle\Exception\Cert\CertCannotBeSavedException;
use Evrinoma\CertBundle\Model\Cert\CertInterface;

interface CommandMediatorInterface
{
    /**
     * @param CertApiDtoInterface $dto
     * @param CertInterface       $entity
     *
     * @return CertInterface
     *
     * @throws CertCannotBeSavedException
     */
    public function onUpdate(CertApiDtoInterface $dto, CertInterface $entity): CertInterface;

    /**
     * @param CertApiDtoInterface $dto
     * @param CertInterface       $entity
     *
     * @throws CertCannotBeRemovedException
     */
    public function onDelete(CertApiDtoInterface $dto, CertInterface $entity): void;

    /**
     * @param CertApiDtoInterface $dto
     * @param CertInterface       $entity
     *
     * @return CertInterface
     *
     * @throws CertCannotBeSavedException
     * @throws CertCannotBeCreatedException
     */
    public function onCreate(CertApiDtoInterface $dto, CertInterface $entity): CertInterface;
}
