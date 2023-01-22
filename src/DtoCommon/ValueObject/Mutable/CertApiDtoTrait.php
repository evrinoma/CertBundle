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

namespace Evrinoma\CertBundle\DtoCommon\ValueObject\Mutable;

use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\DtoCommon\ValueObject\Immutable\CertApiDtoTrait as CertApiDtoImmutableTrait;
use Evrinoma\DtoBundle\Dto\DtoInterface;

trait CertApiDtoTrait
{
    use CertApiDtoImmutableTrait;

    /**
     * @param CertApiDtoInterface $certApiDto
     *
     * @return DtoInterface
     */
    public function setCertApiDto(CertApiDtoInterface $certApiDto): DtoInterface
    {
        $this->certApiDto = $certApiDto;

        return $this;
    }
}
