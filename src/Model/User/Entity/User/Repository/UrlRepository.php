<?php

namespace App\Model\User\Entity\User\Repository;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Repository\Interfaces\UrlRepositoryInterface;
use App\Model\User\Entity\User\Url;
use App\Model\User\Entity\User\UserId;
use App\Model\User\Entity\User\UserUrl;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;


class UrlRepository implements UrlRepositoryInterface
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
        $this->repo = $em->getRepository(Url::class);
    }

    public function hasByUserIdAndUrl(UserId $userId, UserUrl $userUrl): bool
    {
        return $this->repo->createQueryBuilder('a')
                ->select('COUNT(a.id)')
                ->andWhere('a.userUrl = :userUrl')
                ->andWhere('a.user = :userId')
                ->setParameters([':userId' => $userId->getValue(), ':userUrl' => $userUrl->getValue()])
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function get(Id $id): Url
    {
        /** @var Url $url */
        if (!$url = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Url is not found.');
        }
        return $url;
    }

    public function add(Url $url): void
    {
        $this->em->persist($url);
    }

    public function remove(Url $url): void
    {
        $this->em->remove($url);
    }


}
