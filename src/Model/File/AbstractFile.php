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

namespace Evrinoma\CertBundle\Model\File;

use Doctrine\ORM\Mapping as ORM;
use Evrinoma\CertBundle\Model\Cert\CertInterface;
use Evrinoma\UtilsBundle\Entity\ActiveTrait;
use Evrinoma\UtilsBundle\Entity\AttachmentTrait;
use Evrinoma\UtilsBundle\Entity\BriefTrait;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtTrait;
use Evrinoma\UtilsBundle\Entity\IdTrait;
use Evrinoma\UtilsBundle\Entity\ImageTrait;
use Evrinoma\UtilsBundle\Entity\PositionTrait;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractFile implements FileInterface
{
    use ActiveTrait;
    use AttachmentTrait;
    use BriefTrait;
    use CreateUpdateAtTrait;
    use IdTrait;
    use ImageTrait;
    use PositionTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Evrinoma\CertBundle\Model\Cert\CertInterface")
     * @ORM\JoinColumn(name="cert_id", referencedColumnName="id")
     */
    protected ?CertInterface $cert = null;

    /**
     * @return CertInterface
     */
    public function getCert(): CertInterface
    {
        return $this->cert;
    }

    public function resetCert(): FileInterface
    {
        $this->cert = null;

        return $this;
    }

    /**
     * @param CertInterface $cert
     *
     * @return FileInterface
     */
    public function setCert(CertInterface $cert): FileInterface
    {
        $this->cert = $cert;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCert(): bool
    {
        return null !== $this->cert;
    }
}
