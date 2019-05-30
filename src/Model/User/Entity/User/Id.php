<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;

/**
 * Class Id
 * @package App\Model\User\Entity\User
 */
class Id
{
    private $value;

    public function __construct(int $value)
    {
        Assert::notEmpty($value);
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->getValue();
    }
}
