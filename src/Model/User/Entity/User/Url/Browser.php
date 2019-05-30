<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User\Url;

use Webmozart\Assert\Assert;

/**
 * Class Browser
 * @package App\Model\User\Entity\User\Url
 */
class Browser
{
    private $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}