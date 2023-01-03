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

namespace Evrinoma\CertBundle\PreValidator\Cert;

use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Exception\Cert\CertInvalidException;

interface DtoPreValidatorInterface
{
    /**
     * @param CertApiDtoInterface $dto
     *
     * @throws CertInvalidException
     */
    public function onPost(CertApiDtoInterface $dto): void;

    /**
     * @param CertApiDtoInterface $dto
     *
     * @throws CertInvalidException
     */
    public function onPut(CertApiDtoInterface $dto): void;

    /**
     * @param CertApiDtoInterface $dto
     *
     * @throws CertInvalidException
     */
    public function onDelete(CertApiDtoInterface $dto): void;
}
