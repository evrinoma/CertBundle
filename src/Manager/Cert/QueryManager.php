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
use Evrinoma\CertBundle\Repository\Cert\CertQueryRepositoryInterface;

final class QueryManager implements QueryManagerInterface
{
    private CertQueryRepositoryInterface $repository;

    public function __construct(CertQueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CertApiDtoInterface $dto
     *
     * @return array
     *
     * @throws CertNotFoundException
     */
    public function criteria(CertApiDtoInterface $dto): array
    {
        try {
            $cert = $this->repository->findByCriteria($dto);
        } catch (CertNotFoundException $e) {
            throw $e;
        }

        return $cert;
    }

    /**
     * @param CertApiDtoInterface $dto
     *
     * @return CertInterface
     *
     * @throws CertProxyException
     */
    public function proxy(CertApiDtoInterface $dto): CertInterface
    {
        try {
            if ($dto->hasId()) {
                $cert = $this->repository->proxy($dto->idToString());
            } else {
                throw new CertProxyException('Id value is not set while trying get proxy object');
            }
        } catch (CertProxyException $e) {
            throw $e;
        }

        return $cert;
    }

    /**
     * @param CertApiDtoInterface $dto
     *
     * @return CertInterface
     *
     * @throws CertNotFoundException
     */
    public function get(CertApiDtoInterface $dto): CertInterface
    {
        try {
            $cert = $this->repository->find($dto->idToString());
        } catch (CertNotFoundException $e) {
            throw $e;
        }

        return $cert;
    }
}
