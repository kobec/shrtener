<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Url\Create;

use App\Model\Flusher;
use App\Model\User\Entity\User\Repository\Interfaces\UrlRepositoryInterface;
use App\Model\User\Entity\User\Repository\UserRepository;
use App\Model\User\Entity\User\Url;
use App\Model\User\Entity\User\UserId;
use App\Model\User\Entity\User\UserUrl;
use App\Model\User\UseCase\Url\Shortener\Handler as ShortnerHandler;

/**
 * we about to use approach - generating hash url from id
 * so we need to generate short url using current url record ID,
 * this approach require 2 operations:
 * 1. create record with original url,
 * 2. update just created record generate and set short_url using record id
 * in case something wrong - remove just created record and throw Exception
 * Class Handler
 * @package App\Model\User\UseCase\Url\Create
 */
class Handler
{
    private $urls;
    private $users;
    private $flusher;
    private $shortenerHandler;
    private $interval;

    public function __construct(
        string $interval = 'P2Y',//default 2 years
        UrlRepositoryInterface $urls,
        UserRepository $users,
        Flusher $flusher,
        ShortnerHandler $shortenerHandler
    )
    {
        $this->urls = $urls;
        $this->users = $users;
        $this->flusher = $flusher;
        $this->shortenerHandler = $shortenerHandler;
        $this->interval = new \DateInterval($interval);
    }

    public function handle(Dto $dto): void
    {
        //1. create record with original url
        $userUrl = new UserUrl($dto->userUrl);
        $userId = new UserId($dto->userId);
        $user = $this->users->find($dto->userId);
        if ($this->urls->hasByUserIdAndUrl($userId, $userUrl)) {
            throw new \DomainException('Record with this url already exists.');
        }

        /** @var Url $url */
        $url = new Url(
            $user,
            $userUrl,
            (new \DateTimeImmutable())->add($this->interval)
        );
        $this->urls->add($url);
        $this->flusher->flush();
        //2. update just created record - generate and set short_url using record id
        //in case something wrong - remove just created record and throw Exception
        try {
            $urlId = $url->getId();
            $this->shortenerHandler->handle(new \App\Model\User\UseCase\Url\Shortener\Dto($urlId));
        } catch (\Exception $e) {
            $this->urls->remove($url);
            $this->flusher->flush();
            throw new \DomainException($e->getMessage());
        }
    }
}
