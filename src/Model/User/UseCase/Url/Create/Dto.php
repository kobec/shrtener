<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Url\Create;
/**
 * Class Dto
 * @package App\Model\User\UseCase\Url\Create
 */
class Dto
{
    /**
     * @var int
     */
    public $userId;

    /**
     * @var string
     */
    public $userUrl;

    /**
     * @var string
     */
    public $shortUrl;


    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}
