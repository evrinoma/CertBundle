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

namespace Evrinoma\CertBundle\Factory\Cert;

use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Entity\Cert\BaseCert;
use Evrinoma\CertBundle\Model\Cert\CertInterface;

class Factory implements FactoryInterface
{
    private static string $entityClass = BaseCert::class;

    public function __construct(string $entityClass)
    {
        self::$entityClass = $entityClass;
    }

    /**
     * @param CertApiDtoInterface $dto
     *
     * @return CertInterface
     */
    public function create(CertApiDtoInterface $dto): CertInterface
    {
        /* @var BaseCert $cert */
        return new self::$entityClass();
    }
}
