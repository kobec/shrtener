<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Url\Shortener;
/**
 * Class Dto
 * @package App\Model\User\UseCase\Url\Shortener
 */
class Dto
{
    /**
     * @var int
     */
    public $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
