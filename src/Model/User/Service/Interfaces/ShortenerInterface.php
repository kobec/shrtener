<?php

declare(strict_types=1);

namespace App\Model\User\Service\Interfaces;

/**
 * Class Shortener
 * @package App\Model\User\Service
 */
interface ShortenerInterface
{
    /**
     * @param $string
     * @return string
     */
    public function reduce($string): string;

}
