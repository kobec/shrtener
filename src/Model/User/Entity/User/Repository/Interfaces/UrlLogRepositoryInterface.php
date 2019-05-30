<?php

namespace App\Model\User\Entity\User\Repository\Interfaces;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\ShortUrl;
use App\Model\User\Entity\User\Url;
use App\Model\User\Entity\User\UserId;
use App\Model\User\Entity\User\UserUrl;


interface UrlLogRepositoryInterface
{
    public function add(Url\Log $log): void;

    public function remove(Url\Log $log): void;

    public function get(Id $id): Url;

    public function getByShortUrl(ShortUrl $shortUrl): Url;
}
