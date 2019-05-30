<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Url\Log\Create;
/**
 * Class Dto
 * @package App\Model\User\UseCase\Url\Log\Create
 */
class Dto
{
    /**
     * @var int
     */
    public $userUrlId;

    /**
     * @var string
     */
    public $browser;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $ip;


    public function __construct(int $userUrlId, string $ip, string $browser, string $country)
    {
        $this->userUrlId = $userUrlId;
        $this->ip = $ip;
        $this->browser = $browser;
        $this->country = $country;
    }
}
