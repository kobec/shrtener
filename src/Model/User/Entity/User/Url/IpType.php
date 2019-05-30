<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User\Url;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class IpType
 * @package App\Model\User\Entity\User\Url
 */
class IpType extends StringType
{
    public const NAME = 'user_url_log_ip';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Ip ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Ip($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
