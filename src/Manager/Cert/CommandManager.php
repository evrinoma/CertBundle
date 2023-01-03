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

namespace Evrinoma\CertBundle\Manager\Cert;

use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Exception\Cert\CertCannotBeCreatedException;
use Evrinoma\CertBundle\Exception\Cert\CertCannotBeRemovedException;
use Evrinoma\CertBundle\Exception\Cert\CertCannotBeSavedException;
use Evrinoma\CertBundle\Exception\Cert\CertInvalidException;
use Evrinoma\CertBundle\Exception\Cert\CertNotFoundException;
use Evrinoma\CertBundle\Factory\Cert\FactoryInterface;
use Evrinoma\CertBundle\Mediator\Cert\CommandMediatorInterface;
use Evrinoma\CertBundle\Model\Cert\CertInterface;
use Evrinoma\CertBundle\Repository\Cert\CertRepositoryInterface;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface
{
    private CertRepositoryInterface $repository;
    private ValidatorInterface            $validator;
    private FactoryInterface           $factory;
    private CommandMediatorInterface      $mediator;

    public function __construct(ValidatorInterface $validator, CertRepositoryInterface $repository, FactoryInterface $factory, CommandMediatorInterface $mediator)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
    }

    /**
     * @param CertApiDtoInterface $dto
     *
     * @return CertInterface
     *
     * @throws CertInvalidException
     * @throws CertCannotBeCreatedException
     * @throws CertCannotBeSavedException
     */
    public function post(CertApiDtoInterface $dto): CertInterface
    {
        $cert = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $cert);

        $errors = $this->validator->validate($cert);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new CertInvalidException($errorsString);
        }

        $this->repository->save($cert);

        return $cert;
    }

    /**
     * @param CertApiDtoInterface $dto
     *
     * @return CertInterface
     *
     * @throws CertInvalidException
     * @throws CertNotFoundException
     * @throws CertCannotBeSavedException
     */
    public function put(CertApiDtoInterface $dto): CertInterface
    {
        try {
            $cert = $this->repository->find($dto->idToString());
        } catch (CertNotFoundException $e) {
            throw $e;
        }

        $this->mediator->onUpdate($dto, $cert);

        $errors = $this->validator->validate($cert);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new CertInvalidException($errorsString);
        }

        $this->repository->save($cert);

        return $cert;
    }

    /**
     * @param CertApiDtoInterface $dto
     *
     * @throws CertCannotBeRemovedException
     * @throws CertNotFoundException
     */
    public function delete(CertApiDtoInterface $dto): void
    {
        try {
            $cert = $this->repository->find($dto->idToString());
        } catch (CertNotFoundException $e) {
            throw $e;
        }
        $this->mediator->onDelete($dto, $cert);
        try {
            $this->repository->remove($cert);
        } catch (CertCannotBeRemovedException $e) {
            throw $e;
        }
    }
}
