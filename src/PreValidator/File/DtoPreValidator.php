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

namespace Evrinoma\CertBundle\PreValidator\File;

use Evrinoma\CertBundle\Dto\FileApiDtoInterface;
use Evrinoma\CertBundle\Exception\File\FileInvalidException;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\PreValidator\AbstractPreValidator;

class DtoPreValidator extends AbstractPreValidator implements DtoPreValidatorInterface
{
    public function onPost(DtoInterface $dto): void
    {
        $this
            ->checkCert($dto)
            ->checkImage($dto)
            ->checkAttachment($dto)
            ->checkPosition($dto)
            ->checkBrief($dto);
    }

    public function onPut(DtoInterface $dto): void
    {
        $this
            ->checkCert($dto)
            ->checkImage($dto)
            ->checkAttachment($dto)
            ->checkId($dto)
            ->checkPosition($dto)
            ->checkBrief($dto)
            ->checkActive($dto);
    }

    public function onDelete(DtoInterface $dto): void
    {
        $this
            ->checkId($dto);
    }

    private function checkCert(DtoInterface $dto): self
    {
        /** @var FileApiDtoInterface $dto */
        if (!$dto->hasCertApiDto()) {
            throw new FileInvalidException('The Dto has\'t cert');
        }

        return $this;
    }

    private function checkActive(DtoInterface $dto): self
    {
        /** @var FileApiDtoInterface $dto */
        if (!$dto->hasActive()) {
            throw new FileInvalidException('The Dto has\'t active');
        }

        return $this;
    }

    private function checkBrief(DtoInterface $dto): self
    {
        /** @var FileApiDtoInterface $dto */
        if (!$dto->hasBrief()) {
            throw new FileInvalidException('The Dto has\'t brief');
        }

        return $this;
    }

    private function checkId(DtoInterface $dto): self
    {
        /** @var FileApiDtoInterface $dto */
        if (!$dto->hasId()) {
            throw new FileInvalidException('The Dto has\'t ID or class invalid');
        }

        return $this;
    }

    private function checkPosition(DtoInterface $dto): self
    {
        /** @var FileApiDtoInterface $dto */
        if (!$dto->hasPosition()) {
            throw new FileInvalidException('The Dto has\'t position');
        }

        return $this;
    }

    private function checkImage(DtoInterface $dto): self
    {
        /** @var FileApiDtoInterface $dto */
        if (!$dto->hasImage()) {
            throw new FileInvalidException('The Dto has\'t Image file');
        }

        return $this;
    }

    private function checkAttachment(DtoInterface $dto): self
    {
        /** @var FileApiDtoInterface $dto */
        if (!$dto->hasAttachment()) {
            throw new FileInvalidException('The Dto has\'t Attachment file');
        }

        return $this;
    }
}
