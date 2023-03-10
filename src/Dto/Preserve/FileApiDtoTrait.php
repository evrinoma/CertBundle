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

use Evrinoma\CertBundle\DtoCommon\ValueObject\Preserve\CertApiDtoTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\AttachmentTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\BriefTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\ImageTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\PositionTrait;

trait FileApiDtoTrait
{
    use ActiveTrait;
    use AttachmentTrait;
    use BriefTrait;
    use CertApiDtoTrait;
    use IdTrait;
    use ImageTrait;
    use PositionTrait;
}
