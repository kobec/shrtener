<?php

namespace App\Model\User\Entity\User\Repository\Interfaces;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Url;
use App\Model\User\Entity\User\UserId;
use App\Model\User\Entity\User\UserUrl;


interface UrlRepositoryInterface
{
    public function hasByUserIdAndUrl(UserId $userId, UserUrl $userUrl): bool;

    public function add(Url $url): void;

    public function remove(Url $url): void;

    public function get(Id $id): Url;
}
