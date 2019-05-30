<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Url\Shortener;

use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Repository\Interfaces\UrlRepositoryInterface;
use App\Model\User\Entity\User\ShortUrl;
use App\Model\User\Service\Interfaces\ShortenerInterface;

/**
 * Class Handler
 * Generates and set short url hash using existing url entity record
 * @package App\Model\User\UseCase\Url\Shortener
 */
class Handler
{
    private $urls;
    private $shortener;
    private $flusher;

    /**
     * Handler constructor.
     * @param UrlRepositoryInterface $urls
     * @param ShortenerInterface $shortener
     * @param Flusher $flusher
     */
    public function __construct(
        UrlRepositoryInterface $urls,
        ShortenerInterface $shortener,
        Flusher $flusher
    )
    {
        $this->urls = $urls;
        $this->flusher = $flusher;
        $this->shortener = $shortener;
    }

    /**
     * @param Dto $dto
     */
    public function handle(Dto $dto): void
    {
        if (!$url=$this->urls->get(new Id($dto->id))) {
            throw new \DomainException('Unable to generate URL, please trie again');
        }
        $url->setShortUrl(
            new ShortUrl($this->shortener->reduce($url->getId()))
        );
        $this->flusher->flush();
    }
}
