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

namespace Evrinoma\CertBundle\Manager\Cert;

use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Exception\Cert\CertCannotBeRemovedException;
use Evrinoma\CertBundle\Exception\Cert\CertInvalidException;
use Evrinoma\CertBundle\Exception\Cert\CertNotFoundException;
use Evrinoma\CertBundle\Model\Cert\CertInterface;

interface CommandManagerInterface
{
    /**
     * @param CertApiDtoInterface $dto
     *
     * @return CertInterface
     *
     * @throws CertInvalidException
     */
    public function post(CertApiDtoInterface $dto): CertInterface;

    /**
     * @param CertApiDtoInterface $dto
     *
     * @return CertInterface
     *
     * @throws CertInvalidException
     * @throws CertNotFoundException
     */
    public function put(CertApiDtoInterface $dto): CertInterface;

    /**
     * @param CertApiDtoInterface $dto
     *
     * @throws CertCannotBeRemovedException
     * @throws CertNotFoundException
     */
    public function delete(CertApiDtoInterface $dto): void;
}
