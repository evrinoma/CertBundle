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

namespace Evrinoma\CertBundle\Model\Cert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Evrinoma\CertBundle\Model\File\FileInterface;
use Evrinoma\UtilsBundle\Entity\ActiveTrait;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtTrait;
use Evrinoma\UtilsBundle\Entity\IdTrait;
use Evrinoma\UtilsBundle\Entity\PositionTrait;
use Evrinoma\UtilsBundle\Entity\TitleTrait;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractCert implements CertInterface
{
    use ActiveTrait;
    use CreateUpdateAtTrait;
    use IdTrait;
    use PositionTrait;
    use TitleTrait;

    /**
     * @var ArrayCollection|FileInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Evrinoma\CertBundle\Model\File\FileInterface")
     * @ORM\JoinTable(
     *     joinColumns={@ORM\JoinColumn(name="cert_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id")}
     * )
     */
    protected $file;

    public function __construct()
    {
        $this->file = new ArrayCollection();
    }

    /**
     * @return Collection|FileInterface[]
     */
    public function getFile(): Collection
    {
        return $this->file;
    }

    /**
     * @param Collection|FileInterface[] $file
     *
     *  @return CertInterface
     */
    public function setFile($file): CertInterface
    {
        $this->file = $file;

        return $this;
    }
}
