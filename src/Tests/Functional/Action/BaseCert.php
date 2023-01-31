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

namespace Evrinoma\CertBundle\Tests\Functional\Action;

use Evrinoma\CertBundle\Dto\CertApiDto;
use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Tests\Functional\Helper\BaseCertTestTrait;
use Evrinoma\CertBundle\Tests\Functional\ValueObject\Cert\Active;
use Evrinoma\CertBundle\Tests\Functional\ValueObject\Cert\Id;
use Evrinoma\CertBundle\Tests\Functional\ValueObject\Cert\Position;
use Evrinoma\CertBundle\Tests\Functional\ValueObject\Cert\Title;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

class BaseCert extends AbstractServiceTest implements BaseCertTestInterface
{
    use BaseCertTestTrait;

    public const API_GET = 'evrinoma/api/cert';
    public const API_CRITERIA = 'evrinoma/api/cert/criteria';
    public const API_DELETE = 'evrinoma/api/cert/delete';
    public const API_PUT = 'evrinoma/api/cert/save';
    public const API_POST = 'evrinoma/api/cert/create';

    protected static function getDtoClass(): string
    {
        return CertApiDto::class;
    }

    protected static function defaultData(): array
    {
        return [
            CertApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            CertApiDtoInterface::ID => Id::value(),
            CertApiDtoInterface::TITLE => Title::default(),
            CertApiDtoInterface::POSITION => Position::value(),
            CertApiDtoInterface::ACTIVE => Active::value(),
        ];
    }

    public function actionPost(): void
    {
        $this->createCert();
        $this->testResponseStatusCreated();
    }

    public function actionCriteriaNotFound(): void
    {
        $find = $this->criteria([
            CertApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            CertApiDtoInterface::ACTIVE => Active::wrong(),
        ]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);

        $find = $this->criteria([
            CertApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            CertApiDtoInterface::ID => Id::value(),
            CertApiDtoInterface::ACTIVE => Active::block(),
            CertApiDtoInterface::TITLE => Title::wrong(),
        ]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);
    }

    public function actionCriteria(): void
    {
        $find = $this->criteria([
            CertApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            CertApiDtoInterface::ACTIVE => Active::value(),
            CertApiDtoInterface::ID => Id::value(),
        ]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([
            CertApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            CertApiDtoInterface::ACTIVE => Active::delete(),
        ]);
        $this->testResponseStatusOK();
        Assert::assertCount(3, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([
            CertApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            CertApiDtoInterface::ACTIVE => Active::delete(),
            CertApiDtoInterface::TITLE => Title::value(),
        ]);
        $this->testResponseStatusOK();
        Assert::assertCount(2, $find[PayloadModel::PAYLOAD]);
    }

    public function actionDelete(): void
    {
        $find = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::ACTIVE, $find[PayloadModel::PAYLOAD][0][CertApiDtoInterface::ACTIVE]);

        $this->delete(Id::value());
        $this->testResponseStatusAccepted();

        $delete = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::DELETED, $delete[PayloadModel::PAYLOAD][0][CertApiDtoInterface::ACTIVE]);
    }

    public function actionPut(): void
    {
        $query = static::getDefault([
            CertApiDtoInterface::ID => Id::value(),
            CertApiDtoInterface::TITLE => Title::value(),
            CertApiDtoInterface::POSITION => Position::value(),
        ]);

        $find = $this->assertGet(Id::value());

        $updated = $this->put($query);
        $this->testResponseStatusOK();

        $this->compareResults($find, $updated, $query);
    }

    public function actionGet(): void
    {
        $find = $this->assertGet(Id::value());
    }

    public function actionGetNotFound(): void
    {
        $response = $this->get(Id::wrong());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusNotFound();
    }

    public function actionDeleteNotFound(): void
    {
        $response = $this->delete(Id::wrong());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusNotFound();
    }

    public function actionDeleteUnprocessable(): void
    {
        $response = $this->delete(Id::blank());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPutNotFound(): void
    {
        $this->put(static::getDefault([
            CertApiDtoInterface::ID => Id::wrong(),
            CertApiDtoInterface::TITLE => Title::wrong(),
            CertApiDtoInterface::POSITION => Position::wrong(),
        ]));
        $this->testResponseStatusNotFound();
    }

    public function actionPutUnprocessable(): void
    {
        $created = $this->createCert();
        $this->testResponseStatusCreated();
        $this->checkResult($created);

        $query = static::getDefault([
            CertApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][CertApiDtoInterface::ID],
            CertApiDtoInterface::TITLE => Title::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([
            CertApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][CertApiDtoInterface::ID],
            CertApiDtoInterface::POSITION => Position::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $this->createCert();
        $this->testResponseStatusCreated();
        Assert::markTestIncomplete('This test has not been implemented yet.');
    }

    public function actionPostUnprocessable(): void
    {
        $this->postWrong();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankTitle();
        $this->testResponseStatusUnprocessable();
    }
}
