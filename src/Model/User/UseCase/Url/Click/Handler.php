<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Url\Click;

use App\Model\Flusher;
use App\Model\User\Entity\User\Repository\Interfaces\UrlRepositoryInterface;
use App\Model\User\Entity\User\ShortUrl;
use App\Model\User\Service\Interfaces\UserRequestDataInterface;

/**
 * Class Handler
 * @package App\Model\User\UseCase\Url\Click
 */
class Handler
{
    private $urls;
    private $flusher;
    private $userRequestData;
    private $urlLogHandler;

    /**
     * Handler constructor.
     * @param UrlRepositoryInterface $urls
     * @param UserRequestDataInterface $userRequestData
     * @param \App\Model\User\UseCase\Url\Log\Create\Handler $urlLogHandler
     */
    public function __construct(
        UrlRepositoryInterface $urls,
        UserRequestDataInterface $userRequestData,
        \App\Model\User\UseCase\Url\Log\Create\Handler $urlLogHandler
    )
    {
        $this->urls = $urls;
        $this->userRequestData = $userRequestData;
        $this->urlLogHandler = $urlLogHandler;
    }

    /**
     * @param Dto $dto
     * @return string
     */
    public function handle(Dto $dto): string
    {
        //1. check url exists
        if (!$url = $this->urls->getByShortUrl(new ShortUrl($dto->shortUrl))) {
            throw new \DomainException('Unable to fetch URL, please trie again');
        }
        //2. check url not expired
        if ($url->isExpiredTo(new \DateTimeImmutable())) {
            throw new \DomainException('Url lifetime period is already expired');
        }
        //3. write click log to the datatabase
        $this->urlLogHandler->handle(new \App\Model\User\UseCase\Url\Log\Create\Dto(
            $url->getId(),
            $this->userRequestData->getIp(),
            $this->userRequestData->getBrowser(),
            $this->userRequestData->getCountry()
        ));
        return $url->getUserUrl()->getValue();
    }
}
