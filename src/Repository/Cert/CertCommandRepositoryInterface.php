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

namespace Evrinoma\CertBundle\Repository\Cert;

use Evrinoma\CertBundle\Exception\Cert\CertCannotBeRemovedException;
use Evrinoma\CertBundle\Exception\Cert\CertCannotBeSavedException;
use Evrinoma\CertBundle\Model\Cert\CertInterface;

interface CertCommandRepositoryInterface
{
    /**
     * @param CertInterface $cert
     *
     * @return bool
     *
     * @throws CertCannotBeSavedException
     */
    public function save(CertInterface $cert): bool;

    /**
     * @param CertInterface $cert
     *
     * @return bool
     *
     * @throws CertCannotBeRemovedException
     */
    public function remove(CertInterface $cert): bool;
}
