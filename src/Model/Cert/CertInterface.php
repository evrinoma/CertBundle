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

use Doctrine\Common\Collections\Collection;
use Evrinoma\CertBundle\Model\File\FileInterface;
use Evrinoma\UtilsBundle\Entity\ActiveInterface;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtInterface;
use Evrinoma\UtilsBundle\Entity\IdInterface;
use Evrinoma\UtilsBundle\Entity\PositionInterface;
use Evrinoma\UtilsBundle\Entity\TitleInterface;

interface CertInterface extends ActiveInterface, CreateUpdateAtInterface, IdInterface, TitleInterface, PositionInterface
{
    /**
     * @param Collection|FileInterface[] $file
     *
     *  @return CertInterface
     */
    public function setFile($file): CertInterface;

    /**
     * @return Collection|FileInterface[]
     */
    public function getFile(): Collection;
}
