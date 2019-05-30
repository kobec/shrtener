<?php

namespace App\Model\User\Entity\User\Repository;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Repository\Interfaces\UrlLogRepositoryInterface;
use App\Model\User\Entity\User\ShortUrl;
use App\Model\User\Entity\User\Url;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;


class UrlLogRepository implements UrlLogRepositoryInterface
{
    private $em;
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;

    /**
     * UrlRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Url\Log::class);
    }

    public function get(Id $id): Url
    {
        /** @var Url $url */
        if (!$url = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Url is not found.');
        }
        return $url;
    }

    public function getByShortUrl(ShortUrl $shortUrl): Url
    {
        /** @var Url $url */
        if (!$url = $this->repo->findOneBy(['shortUrl' => $shortUrl])) {
            throw new EntityNotFoundException('Url is not found.');
        }
        return $url;
    }

    public function add(Url\Log $log): void
    {
        $this->em->persist($log);
    }

    public function remove(Url\Log $log): void
    {
        $this->em->remove($log);
    }


}
