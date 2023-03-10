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

use Evrinoma\CertBundle\Model\Cert\CertInterface;
use Evrinoma\UtilsBundle\Entity\ActiveInterface;
use Evrinoma\UtilsBundle\Entity\BriefInterface;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtInterface;
use Evrinoma\UtilsBundle\Entity\IdInterface;

interface FileInterface extends ActiveInterface, IdInterface, BriefInterface, CreateUpdateAtInterface
{
    public function resetCert(): FileInterface;

    public function hasCert(): bool;

    public function getCert(): CertInterface;

    public function setCert(CertInterface $cert): FileInterface;
}
