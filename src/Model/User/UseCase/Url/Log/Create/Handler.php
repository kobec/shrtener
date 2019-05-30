<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Url\Log\Create;

use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Repository\Interfaces\UrlLogRepositoryInterface;
use App\Model\User\Entity\User\Repository\Interfaces\UrlRepositoryInterface;
use App\Model\User\Entity\User\Url;

/**
 * Class Handler
 * @package App\Model\User\UseCase\Url\Log\Create
 */
class Handler
{
    private $urls;
    private $logs;
    private $flusher;

    public function __construct(
        UrlRepositoryInterface $urls,
        UrlLogRepositoryInterface $logs,
        Flusher $flusher
    )
    {
        $this->urls = $urls;
        $this->logs = $logs;
        $this->flusher = $flusher;
    }

    public function handle(Dto $dto): void
    {
        $url = $this->urls->get(new Id($dto->userUrlId));
        /** @var Url $url */
        $urlLog = new Url\Log(
            $url,
            new Url\Ip($dto->ip),
            new Url\Browser($dto->browser),
            new Url\Country($dto->country)
        );
        $this->logs->add($urlLog);
        $this->flusher->flush();

    }
}
