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
use Evrinoma\CertBundle\Entity\Cert\BaseCert;
use Evrinoma\TestUtilsBundle\Fixtures\AbstractFixture;

class CertFixtures extends AbstractFixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected static array $data = [
        [
            CertApiDtoInterface::TITLE => 'ite',
            CertApiDtoInterface::POSITION => 1,
            CertApiDtoInterface::ACTIVE => 'a',
        ],
        [
            CertApiDtoInterface::TITLE => 'kzkt',
            CertApiDtoInterface::POSITION => 2,
            CertApiDtoInterface::ACTIVE => 'a',
        ],
        [
            CertApiDtoInterface::TITLE => 'c2m',
            CertApiDtoInterface::POSITION => 3,
            CertApiDtoInterface::ACTIVE => 'a',
        ],
        [
            CertApiDtoInterface::TITLE => 'kzkt2',
            CertApiDtoInterface::POSITION => 1,
            CertApiDtoInterface::ACTIVE => 'd',
        ],
        [
            CertApiDtoInterface::TITLE => 'nvr',
            CertApiDtoInterface::POSITION => 2,
            CertApiDtoInterface::ACTIVE => 'b',
        ],
        [
            CertApiDtoInterface::TITLE => 'nvr2',
            CertApiDtoInterface::POSITION => 3,
            CertApiDtoInterface::ACTIVE => 'd',
        ],
        [
            CertApiDtoInterface::TITLE => 'nvr3',
            CertApiDtoInterface::POSITION => 1,
            CertApiDtoInterface::ACTIVE => 'd',
        ],
    ];

    protected static string $class = BaseCert::class;

    /**
     * @param ObjectManager $manager
     *
     * @return $this
     *
     * @throws \Exception
     */
    protected function create(ObjectManager $manager): self
    {
        $short = self::getReferenceName();
        $i = 0;

        foreach (static::$data as $record) {
            $entity = new static::$class();
            $entity
                ->setActive($record[CertApiDtoInterface::ACTIVE])
                ->setTitle($record[CertApiDtoInterface::TITLE])
                ->setPosition($record[CertApiDtoInterface::POSITION])
                ->setCreatedAt(new \DateTimeImmutable())
            ;

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
        return 0;
    }
}
