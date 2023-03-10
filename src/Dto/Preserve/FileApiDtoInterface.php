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

namespace Evrinoma\CertBundle\Dto\Preserve;

use Evrinoma\CertBundle\DtoCommon\ValueObject\Mutable\CertApiDtoInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ActiveInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\AttachmentInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\BriefInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ImageInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionInterface;

interface FileApiDtoInterface extends IdInterface, BriefInterface, ActiveInterface, ImageInterface, PositionInterface, AttachmentInterface, CertApiDtoInterface
{
}
