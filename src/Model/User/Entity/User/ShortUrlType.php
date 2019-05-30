<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class ShortUrlType
 * @package App\Model\User\Entity\User
 */
class ShortUrlType extends StringType
{
    public const NAME = 'short_url';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ShortUrl ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new ShortUrl($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
