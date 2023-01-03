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

namespace Evrinoma\CertBundle\Tests\Functional\Helper;

use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

trait BaseCertTestTrait
{
    protected function assertGet(string $id): array
    {
        $find = $this->get($id);
        $this->testResponseStatusOK();

        $this->checkResult($find);

        return $find;
    }

    protected function createCert(): array
    {
        $query = static::getDefault();

        return $this->post($query);
    }

    protected function compareResults(array $value, array $entity, array $query): void
    {
        Assert::assertEquals($value[PayloadModel::PAYLOAD][0][CertApiDtoInterface::ID], $entity[PayloadModel::PAYLOAD][0][CertApiDtoInterface::ID]);
        Assert::assertEquals($query[CertApiDtoInterface::TITLE], $entity[PayloadModel::PAYLOAD][0][CertApiDtoInterface::TITLE]);
        Assert::assertEquals($query[CertApiDtoInterface::POSITION], $entity[PayloadModel::PAYLOAD][0][CertApiDtoInterface::POSITION]);
    }

    protected function createConstraintBlankTitle(): array
    {
        $query = static::getDefault([CertApiDtoInterface::TITLE => '']);

        return $this->post($query);
    }

    protected function checkResult($entity): void
    {
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $entity);
        Assert::assertCount(1, $entity[PayloadModel::PAYLOAD]);
        $this->checkCert($entity[PayloadModel::PAYLOAD][0]);
    }

    protected function checkCert($entity): void
    {
        Assert::assertArrayHasKey(CertApiDtoInterface::ID, $entity);
        Assert::assertArrayHasKey(CertApiDtoInterface::TITLE, $entity);
        Assert::assertArrayHasKey(CertApiDtoInterface::ACTIVE, $entity);
        Assert::assertArrayHasKey(CertApiDtoInterface::POSITION, $entity);
    }
}
