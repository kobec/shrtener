<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class UserUrlType extends StringType
{
    public const NAME = 'user_url';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof UserUrl ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new UserUrl($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
