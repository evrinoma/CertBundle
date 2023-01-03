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

namespace Evrinoma\CertBundle\Manager\File;

use Evrinoma\CertBundle\Dto\FileApiDtoInterface;
use Evrinoma\CertBundle\Exception\File\FileCannotBeCreatedException;
use Evrinoma\CertBundle\Exception\File\FileCannotBeRemovedException;
use Evrinoma\CertBundle\Exception\File\FileCannotBeSavedException;
use Evrinoma\CertBundle\Exception\File\FileInvalidException;
use Evrinoma\CertBundle\Exception\File\FileNotFoundException;
use Evrinoma\CertBundle\Factory\File\FactoryInterface;
use Evrinoma\CertBundle\Manager\Cert\QueryManagerInterface as CertQueryManagerInterface;
use Evrinoma\CertBundle\Mediator\File\CommandMediatorInterface;
use Evrinoma\CertBundle\Model\File\FileInterface;
use Evrinoma\CertBundle\Repository\File\FileRepositoryInterface;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface
{
    private FileRepositoryInterface $repository;
    private ValidatorInterface            $validator;
    private FactoryInterface           $factory;
    private CommandMediatorInterface      $mediator;
    private CertQueryManagerInterface $certQueryManager;

    public function __construct(ValidatorInterface $validator, FileRepositoryInterface $repository, FactoryInterface $factory, CommandMediatorInterface $mediator, CertQueryManagerInterface $certQueryManager)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
        $this->certQueryManager = $certQueryManager;
    }

    /**
     * @param FileApiDtoInterface $dto
     *
     * @return FileInterface
     *
     * @throws FileInvalidException
     * @throws FileCannotBeCreatedException
     * @throws FileCannotBeSavedException
     */
    public function post(FileApiDtoInterface $dto): FileInterface
    {
        $file = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $file);

        try {
            $file->setCert($this->certQueryManager->proxy($dto->getCertApiDto()));
        } catch (\Exception $e) {
            throw new FileCannotBeCreatedException($e->getMessage());
        }

        $errors = $this->validator->validate($file);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new FileInvalidException($errorsString);
        }

        $this->repository->save($file);

        return $file;
    }

    /**
     * @param FileApiDtoInterface $dto
     *
     * @return FileInterface
     *
     * @throws FileInvalidException
     * @throws FileNotFoundException
     * @throws FileCannotBeSavedException
     */
    public function put(FileApiDtoInterface $dto): FileInterface
    {
        try {
            $file = $this->repository->find($dto->idToString());
        } catch (FileNotFoundException $e) {
            throw $e;
        }

        try {
            $file->setCert($this->certQueryManager->proxy($dto->getCertApiDto()));
        } catch (\Exception $e) {
            throw new FileCannotBeSavedException($e->getMessage());
        }

        $this->mediator->onUpdate($dto, $file);

        $errors = $this->validator->validate($file);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new FileInvalidException($errorsString);
        }

        $this->repository->save($file);

        return $file;
    }

    /**
     * @param FileApiDtoInterface $dto
     *
     * @throws FileCannotBeRemovedException
     * @throws FileNotFoundException
     */
    public function delete(FileApiDtoInterface $dto): void
    {
        try {
            $file = $this->repository->find($dto->idToString());
        } catch (FileNotFoundException $e) {
            throw $e;
        }
        $this->mediator->onDelete($dto, $file);
        try {
            $this->repository->remove($file);
        } catch (FileCannotBeRemovedException $e) {
            throw $e;
        }
    }
}
