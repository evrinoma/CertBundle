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

namespace Evrinoma\CertBundle\Serializer;

interface GroupInterface
{
    public const API_POST_CERT = 'API_POST_CERT';
    public const API_PUT_CERT = 'API_PUT_CERT';
    public const API_GET_CERT = 'API_GET_CERT';
    public const API_CRITERIA_CERT = self::API_GET_CERT;
    public const APP_GET_BASIC_CERT = 'APP_GET_BASIC_CERT';

    public const API_POST_CERT_FILE = 'API_POST_CERT_FILE';
    public const API_PUT_CERT_FILE = 'API_PUT_CERT_FILE';
    public const API_GET_CERT_FILE = 'API_GET_CERT_FILE';
    public const API_CRITERIA_CERT_FILE = self::API_GET_CERT_FILE;
    public const APP_GET_BASIC_CERT_FILE = 'APP_GET_BASIC_CERT_FILE';
}
