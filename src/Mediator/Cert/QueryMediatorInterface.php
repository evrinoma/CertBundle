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
use Evrinoma\UtilsBundle\QueryBuilder\QueryBuilderInterface;

interface QueryMediatorInterface
{
    /**
     * @return string
     */
    public function alias(): string;

    /**
     * @param CertApiDtoInterface   $dto
     * @param QueryBuilderInterface $builder
     *
     * @return mixed
     */
    public function createQuery(CertApiDtoInterface $dto, QueryBuilderInterface $builder): void;

    /**
     * @param CertApiDtoInterface   $dto
     * @param QueryBuilderInterface $builder
     *
     * @return array
     */
    public function getResult(CertApiDtoInterface $dto, QueryBuilderInterface $builder): array;
}
