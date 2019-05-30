<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Service\Interfaces\ShortenerInterface;
use Hashids\Hashids;

/**
 * Class Shortener
 * @package App\Model\User\Service
 */
class Shortener implements ShortenerInterface
{
    protected $hashids;

    /**
     * Shortener constructor.
     * @param Hashids $hashids
     * @see https://github.com/ivanakimov/hashids.php
     */
    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    /**
     * @param $string
     * @return string
     */
    public function reduce($string): string
    {
        //$reduced = base_convert($string,10,36);
        $reduced = $this->hashids->encode($string);
        if ($reduced === false) {
            throw new \RuntimeException('Unable to generate URL, please trie again');
        }
        return $reduced;
    }
}
