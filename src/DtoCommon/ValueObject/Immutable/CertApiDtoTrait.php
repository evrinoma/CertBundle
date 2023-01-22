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

namespace Evrinoma\CertBundle\DtoCommon\ValueObject\Immutable;

use Evrinoma\CertBundle\Dto\CertApiDto;
use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Symfony\Component\HttpFoundation\Request;

trait CertApiDtoTrait
{
    protected ?CertApiDtoInterface $certApiDto = null;

    protected static string $classCertApiDto = CertApiDto::class;

    public function genRequestCertApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $cert = $request->get(CertApiDtoInterface::CERT);
            if ($cert) {
                $newRequest = $this->getCloneRequest();
                $cert[DtoInterface::DTO_CLASS] = static::$classCertApiDto;
                $newRequest->request->add($cert);

                yield $newRequest;
            }
        }
    }

    public function hasCertApiDto(): bool
    {
        return null !== $this->certApiDto;
    }

    public function getCertApiDto(): CertApiDtoInterface
    {
        return $this->certApiDto;
    }
}
