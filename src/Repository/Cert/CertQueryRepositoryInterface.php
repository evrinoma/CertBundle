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

namespace Evrinoma\CertBundle\Repository\Cert;

use Doctrine\ORM\Exception\ORMException;
use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Exception\Cert\CertNotFoundException;
use Evrinoma\CertBundle\Exception\Cert\CertProxyException;
use Evrinoma\CertBundle\Model\Cert\CertInterface;

interface CertQueryRepositoryInterface
{
    /**
     * @param CertApiDtoInterface $dto
     *
     * @return array
     *
     * @throws CertNotFoundException
     */
    public function findByCriteria(CertApiDtoInterface $dto): array;

    /**
     * @param string $id
     * @param null   $lockMode
     * @param null   $lockVersion
     *
     * @return CertInterface
     *
     * @throws CertNotFoundException
     */
    public function find(string $id, $lockMode = null, $lockVersion = null): CertInterface;

    /**
     * @param string $id
     *
     * @return CertInterface
     *
     * @throws CertProxyException
     * @throws ORMException
     */
    public function proxy(string $id): CertInterface;
}
