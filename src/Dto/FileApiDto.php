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

namespace Evrinoma\CertBundle\Dto;

use Evrinoma\DtoBundle\Annotation\Dto;
use Evrinoma\DtoBundle\Dto\AbstractDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\AttachmentTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\BriefTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\ImageTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class FileApiDto extends AbstractDto implements FileApiDtoInterface
{
    use ActiveTrait;
    use AttachmentTrait;
    use BriefTrait;
    use IdTrait;
    use ImageTrait;
    use PositionTrait;

    /**
     * @Dto(class="Evrinoma\CertBundle\Dto\CertApiDto", generator="genRequestCertApiDto")
     */
    private ?CertApiDtoInterface $certApiDto = null;

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

    public function hasCertApiDto(): bool
    {
        return null !== $this->certApiDto;
    }

    public function getCertApiDto(): CertApiDtoInterface
    {
        return $this->certApiDto;
    }

    public function genRequestCertApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $type = $request->get(CertApiDtoInterface::CERT);
            if ($type) {
                $newRequest = $this->getCloneRequest();
                $type[DtoInterface::DTO_CLASS] = CertApiDto::class;
                $newRequest->request->add($type);

                yield $newRequest;
            }
        }
    }

    public function toDto(Request $request): DtoInterface
    {
        $class = $request->get(DtoInterface::DTO_CLASS);

        if ($class === $this->getClass()) {
            $id = $request->get(FileApiDtoInterface::ID);
            $active = $request->get(FileApiDtoInterface::ACTIVE);
            $brief = $request->get(FileApiDtoInterface::BRIEF);
            $position = $request->get(FileApiDtoInterface::POSITION);

            $files = ($request->files->has($this->getClass())) ? $request->files->get($this->getClass()) : [];

            try {
                if (\array_key_exists(FileApiDtoInterface::IMAGE, $files)) {
                    $image = $files[FileApiDtoInterface::IMAGE];
                } else {
                    $image = $request->get(FileApiDtoInterface::IMAGE);
                    if (null !== $image) {
                        $image = new File($image);
                    }
                }
            } catch (\Exception $e) {
                $image = null;
            }

            try {
                if (\array_key_exists(FileApiDtoInterface::ATTACHMENT, $files)) {
                    $attachment = $files[FileApiDtoInterface::ATTACHMENT];
                } else {
                    $attachment = $request->get(FileApiDtoInterface::ATTACHMENT);
                    if (null !== $attachment) {
                        $attachment = new File($attachment);
                    }
                }
            } catch (\Exception $e) {
                $attachment = null;
            }

            if ($brief) {
                $this->setBrief($brief);
            }
            if ($active) {
                $this->setActive($active);
            }
            if ($id) {
                $this->setId($id);
            }
            if ($attachment) {
                $this->setAttachment($attachment);
            }
            if ($image) {
                $this->setImage($image);
            }
            if ($position) {
                $this->setPosition($position);
            }
        }

        return $this;
    }
}
