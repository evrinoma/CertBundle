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
use Doctrine\ORM\ORMInvalidArgumentException;
use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Exception\Cert\CertCannotBeSavedException;
use Evrinoma\CertBundle\Exception\Cert\CertNotFoundException;
use Evrinoma\CertBundle\Exception\Cert\CertProxyException;
use Evrinoma\CertBundle\Mediator\Cert\QueryMediatorInterface;
use Evrinoma\CertBundle\Model\Cert\CertInterface;

trait CertRepositoryTrait
{
    private QueryMediatorInterface $mediator;

    /**
     * @param CertInterface $cert
     *
     * @return bool
     *
     * @throws CertCannotBeSavedException
     * @throws ORMException
     */
    public function save(CertInterface $cert): bool
    {
        try {
            $this->persistWrapped($cert);
        } catch (ORMInvalidArgumentException $e) {
            throw new CertCannotBeSavedException($e->getMessage());
        }

        return true;
    }

    /**
     * @param CertInterface $cert
     *
     * @return bool
     */
    public function remove(CertInterface $cert): bool
    {
        return true;
    }

    /**
     * @param CertApiDtoInterface $dto
     *
     * @return array
     *
     * @throws CertNotFoundException
     */
    public function findByCriteria(CertApiDtoInterface $dto): array
    {
        $builder = $this->createQueryBuilderWrapped($this->mediator->alias());

        $this->mediator->createQuery($dto, $builder);

        $certs = $this->mediator->getResult($dto, $builder);

        if (0 === \count($certs)) {
            throw new CertNotFoundException('Cannot find cert by findByCriteria');
        }

        return $certs;
    }

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return mixed
     *
     * @throws CertNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): CertInterface
    {
        /** @var CertInterface $cert */
        $cert = $this->findWrapped($id);

        if (null === $cert) {
            throw new CertNotFoundException("Cannot find cert with id $id");
        }

        return $cert;
    }

    /**
     * @param string $id
     *
     * @return CertInterface
     *
     * @throws CertProxyException
     * @throws ORMException
     */
    public function proxy(string $id): CertInterface
    {
        $cert = $this->referenceWrapped($id);

        if (!$this->containsWrapped($cert)) {
            throw new CertProxyException("Proxy doesn't exist with $id");
        }

        return $cert;
    }
}
