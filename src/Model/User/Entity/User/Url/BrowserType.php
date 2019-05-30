<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User\Url;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class BrowserType
 * @package App\Model\User\Entity\User\Url
 */
class BrowserType extends StringType
{
    public const NAME = 'user_url_log_browser';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Browser ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Browser($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
