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

use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Dto\FileApiDto;
use Evrinoma\CertBundle\Dto\FileApiDtoInterface;
use Evrinoma\CertBundle\Tests\Functional\Helper\BaseFileTestTrait;
use Evrinoma\CertBundle\Tests\Functional\ValueObject\File\Active;
use Evrinoma\CertBundle\Tests\Functional\ValueObject\File\Attachment;
use Evrinoma\CertBundle\Tests\Functional\ValueObject\File\Brief;
use Evrinoma\CertBundle\Tests\Functional\ValueObject\File\Id;
use Evrinoma\CertBundle\Tests\Functional\ValueObject\File\Image;
use Evrinoma\CertBundle\Tests\Functional\ValueObject\File\Position;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\TestUtilsBundle\Browser\ApiBrowserTestInterface;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

class BaseFile extends AbstractServiceTest implements BaseFileTestInterface
{
    use BaseFileTestTrait;

    public const API_GET = 'evrinoma/api/cert/file';
    public const API_CRITERIA = 'evrinoma/api/cert/file/criteria';
    public const API_DELETE = 'evrinoma/api/cert/file/delete';
    public const API_PUT = 'evrinoma/api/cert/file/save';
    public const API_POST = 'evrinoma/api/cert/file/create';

    protected string $methodPut = ApiBrowserTestInterface::POST;

    protected static array $header = ['CONTENT_TYPE' => 'multipart/form-data'];
    protected bool $form = true;

    protected static function getDtoClass(): string
    {
        return FileApiDto::class;
    }

    protected static function defaultData(): array
    {
        static::initFiles();

        return [
            FileApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            FileApiDtoInterface::ID => Id::value(),
            FileApiDtoInterface::BRIEF => Brief::default(),
            FileApiDtoInterface::ATTACHMENT => Attachment::value(),
            FileApiDtoInterface::POSITION => Position::value(),
            FileApiDtoInterface::ACTIVE => Active::value(),
            FileApiDtoInterface::IMAGE => Image::default(),
            CertApiDtoInterface::CERT => BaseCert::defaultData(),
        ];
    }

    public function actionPost(): void
    {
        $this->createFile();
        $this->testResponseStatusCreated();

        static::$files = [];
        $query = static::getDefault([
            FileApiDtoInterface::BRIEF => str_shuffle(Brief::value()),
        ]);

        $this->post($query);
        $this->testResponseStatusCreated();
    }

    public function actionCriteriaNotFound(): void
    {
        $find = $this->criteria([
            FileApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            FileApiDtoInterface::ACTIVE => Active::wrong(),
        ]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);

        $find = $this->criteria([
            FileApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            FileApiDtoInterface::ID => Id::value(),
            FileApiDtoInterface::ACTIVE => Active::block(),
        ]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);
    }

    public function actionCriteria(): void
    {
        $find = $this->criteria([
            FileApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            FileApiDtoInterface::ACTIVE => Active::value(),
            FileApiDtoInterface::ID => Id::value(),
        ]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([
            FileApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            FileApiDtoInterface::ACTIVE => Active::delete(),
        ]);
        $this->testResponseStatusOK();
        Assert::assertCount(3, $find[PayloadModel::PAYLOAD]);
    }

    public function actionDelete(): void
    {
        $find = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::ACTIVE, $find[PayloadModel::PAYLOAD][0][FileApiDtoInterface::ACTIVE]);

        $this->delete(Id::value());
        $this->testResponseStatusAccepted();

        $delete = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::DELETED, $delete[PayloadModel::PAYLOAD][0][FileApiDtoInterface::ACTIVE]);
    }

    public function actionPut(): void
    {
        $query = static::getDefault([
            FileApiDtoInterface::ID => Id::value(),
            FileApiDtoInterface::BRIEF => Brief::value(),
        ]);

        $find = $this->assertGet(Id::value());

        $updated = $this->put($query);
        $this->testResponseStatusOK();

        $this->compareResults($find, $updated, $query);

        static::$files = [];

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
            FileApiDtoInterface::ID => Id::wrong(),
            FileApiDtoInterface::BRIEF => Brief::wrong(),
        ]));
        $this->testResponseStatusNotFound();
    }

    public function actionPutUnprocessable(): void
    {
        $created = $this->createFile();
        $this->testResponseStatusCreated();
        $this->checkResult($created);

        $query = static::getDefault([
            FileApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][FileApiDtoInterface::ID],
            FileApiDtoInterface::BRIEF => Brief::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([
            FileApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][FileApiDtoInterface::ID],
            FileApiDtoInterface::POSITION => Position::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        unset(static::$files[static::getDtoClass()][FileApiDtoInterface::IMAGE]);
        $query = static::getDefault([
            FileApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][FileApiDtoInterface::ID],
            FileApiDtoInterface::IMAGE => Image::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        unset(static::$files[static::getDtoClass()][FileApiDtoInterface::ATTACHMENT]);
        $query = static::getDefault([
            FileApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][FileApiDtoInterface::ID],
            FileApiDtoInterface::ATTACHMENT => Attachment::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([
            FileApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][FileApiDtoInterface::ID],
            FileApiDtoInterface::IMAGE => Image::blank(),
            FileApiDtoInterface::ATTACHMENT => Attachment::blank(),
        ]);
        static::$files[static::getDtoClass()] = [];

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([
            FileApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][FileApiDtoInterface::ID],
            FileApiDtoInterface::IMAGE => Image::blank(),
            FileApiDtoInterface::ATTACHMENT => Attachment::blank(),
        ]);
        static::$files = [];

        $this->put($query);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $created = $this->createFile();
        $this->testResponseStatusCreated();

        Assert::markTestIncomplete('This test has not been implemented yet.');
    }

    public function actionPostUnprocessable(): void
    {
        $this->postWrong();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankBrief();
        $this->testResponseStatusUnprocessable();
    }
}
