<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User\Url;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class CountryType
 * @package App\Model\User\Entity\User\Url
 */
class CountryType extends StringType
{
    public const NAME = 'user_url_log_country';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Country ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Country($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
