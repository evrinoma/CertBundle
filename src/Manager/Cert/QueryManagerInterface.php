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
use Evrinoma\CertBundle\Exception\Cert\CertNotFoundException;
use Evrinoma\CertBundle\Exception\Cert\CertProxyException;
use Evrinoma\CertBundle\Model\Cert\CertInterface;

interface QueryManagerInterface
{
    /**
     * @param CertApiDtoInterface $dto
     *
     * @return array
     *
     * @throws CertNotFoundException
     */
    public function criteria(CertApiDtoInterface $dto): array;

    /**
     * @param CertApiDtoInterface $dto
     *
     * @return CertInterface
     *
     * @throws CertNotFoundException
     */
    public function get(CertApiDtoInterface $dto): CertInterface;

    /**
     * @param CertApiDtoInterface $dto
     *
     * @return CertInterface
     *
     * @throws CertProxyException
     */
    public function proxy(CertApiDtoInterface $dto): CertInterface;
}
