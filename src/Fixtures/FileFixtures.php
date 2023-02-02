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

namespace Evrinoma\CertBundle\Fixtures;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Dto\FileApiDtoInterface;
use Evrinoma\CertBundle\Entity\File\BaseFile;
use Evrinoma\TestUtilsBundle\Fixtures\AbstractFixture;

class FileFixtures extends AbstractFixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected static array $data = [
        [
            FileApiDtoInterface::BRIEF => 'ite',
            FileApiDtoInterface::ATTACHMENT => 'PATH://TO_ATTACHMENT',
            FileApiDtoInterface::ACTIVE => 'a',
            FileApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            FileApiDtoInterface::POSITION => 1,
            CertApiDtoInterface::CERT => 0,
        ],
        [
            FileApiDtoInterface::BRIEF => 'kzkt',
            FileApiDtoInterface::ATTACHMENT => 'PATH://TO_ATTACHMENT',
            FileApiDtoInterface::ACTIVE => 'a',
            FileApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            FileApiDtoInterface::POSITION => 2,
            CertApiDtoInterface::CERT => 1,
        ],
        [
            FileApiDtoInterface::BRIEF => 'c2m',
            FileApiDtoInterface::ATTACHMENT => 'PATH://TO_ATTACHMENT',
            FileApiDtoInterface::ACTIVE => 'a',
            FileApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            FileApiDtoInterface::POSITION => 3,
            CertApiDtoInterface::CERT => 0,
        ],
        [
            FileApiDtoInterface::BRIEF => 'kzkt2',
            FileApiDtoInterface::ATTACHMENT => 'PATH://TO_ATTACHMENT',
            FileApiDtoInterface::ACTIVE => 'd',
            FileApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            FileApiDtoInterface::POSITION => 4,
            CertApiDtoInterface::CERT => 1,
            ],
        [
            FileApiDtoInterface::BRIEF => 'nvr',
            FileApiDtoInterface::ATTACHMENT => 'PATH://TO_ATTACHMENT',
            FileApiDtoInterface::ACTIVE => 'b',
            FileApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            FileApiDtoInterface::POSITION => 5,
            CertApiDtoInterface::CERT => 0,
        ],
        [
            FileApiDtoInterface::BRIEF => 'nvr2',
            FileApiDtoInterface::ATTACHMENT => 'PATH://TO_ATTACHMENT',
            FileApiDtoInterface::ACTIVE => 'd',
            FileApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            FileApiDtoInterface::POSITION => 6,
            CertApiDtoInterface::CERT => 1,
            ],
        [
            FileApiDtoInterface::BRIEF => 'nvr3',
            FileApiDtoInterface::ATTACHMENT => 'PATH://TO_ATTACHMENT',
            FileApiDtoInterface::ACTIVE => 'd',
            FileApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            FileApiDtoInterface::POSITION => 7,
            CertApiDtoInterface::CERT => 2,
        ],
    ];

    protected static string $class = BaseFile::class;

    /**
     * @param ObjectManager $manager
     *
     * @return $this
     *
     * @throws \Exception
     */
    protected function create(ObjectManager $manager): self
    {
        $short = static::getReferenceName();
        $shortCert = CertFixtures::getReferenceName();
        $i = 0;

        foreach ($this->getData() as $record) {
            $entity = $this->getEntity();
            $entity
                ->setCert($this->getReference($shortCert.$record[CertApiDtoInterface::CERT]))
                ->setActive($record[FileApiDtoInterface::ACTIVE])
                ->setBrief($record[FileApiDtoInterface::BRIEF])
                ->setPosition($record[FileApiDtoInterface::POSITION])
                ->setImage($record[FileApiDtoInterface::IMAGE])
                ->setAttachment($record[FileApiDtoInterface::ATTACHMENT])
                ->setCreatedAt(new \DateTimeImmutable())
            ;

            $this->expandEntity($entity, $record);

            $this->addReference($short.$i, $entity);
            $manager->persist($entity);
            ++$i;
        }

        return $this;
    }

    public static function getGroups(): array
    {
        return [
            FixtureInterface::CERT_FIXTURES, FixtureInterface::FILE_FIXTURES,
        ];
    }

    public function getOrder()
    {
        return 100;
    }
}
