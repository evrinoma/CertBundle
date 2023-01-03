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

namespace Evrinoma\CertBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping;
use Evrinoma\CertBundle\DependencyInjection\EvrinomaCertExtension;
use Evrinoma\CertBundle\Entity\File\BaseFile;
use Evrinoma\CertBundle\Model\Cert\CertInterface;
use Evrinoma\CertBundle\Model\File\FileInterface;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractMapEntity;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MapEntityPass extends AbstractMapEntity implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ('orm' === $container->getParameter('evrinoma.cert.storage')) {
            $this->setContainer($container);

            $driver = $container->findDefinition('doctrine.orm.default_metadata_driver');
            $referenceAnnotationReader = new Reference('annotations.reader');

            $this->cleanMetadata($driver, [EvrinomaCertExtension::ENTITY]);

            $entityFile = BaseFile::class;

            $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/File', '%s/Entity/File');

            $this->addResolveTargetEntity([$entityFile => [FileInterface::class => []]], false);

            $entityCert = $container->getParameter('evrinoma.cert.entity');
            if (str_contains($entityCert, EvrinomaCertExtension::ENTITY)) {
                $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/Cert', '%s/Entity/Cert');
            }
            $this->addResolveTargetEntity([$entityCert => [CertInterface::class => []]], false);

            $mapping = $this->getMapping($entityFile);
            $this->addResolveTargetEntity([$entityFile => [FileInterface::class => ['inherited' => true, 'joinTable' => $mapping]]], false);
        }
    }

    private function getMapping(string $className): array
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($className);
        $joinTableAttribute = $annotationReader->getClassAnnotation($reflectionClass, Mapping\Table::class);

        return ($joinTableAttribute) ? ['name' => $joinTableAttribute->name] : [];
    }
}
