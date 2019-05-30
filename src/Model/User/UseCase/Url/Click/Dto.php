<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Url\Click;
/**
 * Class Dto
 * @package App\Model\User\UseCase\Url\Click
 */
class Dto
{
    /**
     * @var int
     */
    public $shortUrl;

    public function __construct(string $shortUrl)
    {
        $this->shortUrl = $shortUrl;
    }
}
