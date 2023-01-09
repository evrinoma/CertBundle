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

namespace Evrinoma\CertBundle\Mediator\File;

use Evrinoma\CertBundle\Dto\FileApiDtoInterface;
use Evrinoma\CertBundle\Exception\File\FileCannotBeCreatedException;
use Evrinoma\CertBundle\Exception\File\FileCannotBeSavedException;
use Evrinoma\CertBundle\Manager\Cert\QueryManagerInterface as CertQueryManagerInterface;
use Evrinoma\CertBundle\Model\File\FileInterface;
use Evrinoma\CertBundle\System\FileSystemInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
    private FileSystemInterface $fileSystem;
    private CertQueryManagerInterface $certQueryManager;

    public function __construct(FileSystemInterface $fileSystem, CertQueryManagerInterface $certQueryManager)
    {
        $this->fileSystem = $fileSystem;
        $this->certQueryManager = $certQueryManager;
    }

    public function onUpdate(DtoInterface $dto, $entity): FileInterface
    {
        /* @var $dto FileApiDtoInterface */
        $fileImage = $this->fileSystem->save($dto->getImage());
        $fileAttachment = $this->fileSystem->save($dto->getAttachment());

        try {
            $entity->setCert($this->certQueryManager->proxy($dto->getCertApiDto()));
        } catch (\Exception $e) {
            throw new FileCannotBeSavedException($e->getMessage());
        }

        $entity
            ->setAttachment($fileAttachment->getPathname())
            ->setBrief($dto->getBrief())
            ->setPosition($dto->getPosition())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setImage($fileImage->getPathname())
            ->setActive($dto->getActive());

        return $entity;
    }

    public function onDelete(DtoInterface $dto, $entity): void
    {
        $entity
            ->setActiveToDelete();
    }

    public function onCreate(DtoInterface $dto, $entity): FileInterface
    {
        /* @var $dto FileApiDtoInterface */
        $fileImage = $this->fileSystem->save($dto->getImage());
        $fileAttachment = $this->fileSystem->save($dto->getAttachment());

        try {
            $entity->setCert($this->certQueryManager->proxy($dto->getCertApiDto()));
        } catch (\Exception $e) {
            throw new FileCannotBeCreatedException($e->getMessage());
        }

        $entity
            ->setAttachment($fileAttachment->getPathname())
            ->setBrief($dto->getBrief())
            ->setPosition($dto->getPosition())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setImage($fileImage->getPathname())
            ->setActiveToActive();

        return $entity;
    }
}
