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
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\PreValidator\AbstractPreValidator;

class DtoPreValidator extends AbstractPreValidator implements DtoPreValidatorInterface
{
    public function onPost(DtoInterface $dto): void
    {
        $this
            ->checkTitle($dto)
            ->checkPosition($dto)
            ->checkCert($dto);
    }

    public function onPut(DtoInterface $dto): void
    {
        $this
            ->checkId($dto)
            ->checkTitle($dto)
            ->checkActive($dto)
            ->checkPosition($dto)
            ->checkCert($dto);
    }

    public function onDelete(DtoInterface $dto): void
    {
        $this
            ->checkId($dto);
    }

    private function checkPosition(DtoInterface $dto): self
    {
        /** @var CertApiDtoInterface $dto */
        if (!$dto->hasPosition()) {
            throw new CertInvalidException('The Dto has\'t position');
        }

        return $this;
    }

    private function checkTitle(DtoInterface $dto): self
    {
        /** @var CertApiDtoInterface $dto */
        if (!$dto->hasTitle()) {
            throw new CertInvalidException('The Dto has\'t title');
        }

        return $this;
    }

    private function checkActive(DtoInterface $dto): self
    {
        /** @var CertApiDtoInterface $dto */
        if (!$dto->hasActive()) {
            throw new CertInvalidException('The Dto has\'t active');
        }

        return $this;
    }

    private function checkCert(DtoInterface $dto): self
    {
        /* @var CertApiDtoInterface $dto */

        return $this;
    }

    private function checkId(DtoInterface $dto): self
    {
        /** @var CertApiDtoInterface $dto */
        if (!$dto->hasId()) {
            throw new CertInvalidException('The Dto has\'t ID or class invalid');
        }

        return $this;
    }
}
