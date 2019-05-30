<?php

declare(strict_types=1);

namespace App\ReadModel\User\Url;

use App\Model\User\Entity\User\ShortUrl;
use App\Model\User\Entity\User\Url;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserId;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UrlFetcher
 * @package App\ReadModel\User\Url
 */
class UrlFetcher
{
    private $connection;
    private $paginator;
    private $repository;

    public function __construct(Connection $connection, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(Url::class);
        $this->paginator = $paginator;
    }

    /**
     * @param UserId|null $userId
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all(UserId $userId = null, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'user_url',
                'short_url',
                'expires',
                'created_at'
            )
            ->from('user_url');
        //pass user_id to the where clause(if defined)
        if (null !== $userId) {
            $qb->andWhere($qb->expr()->like('user_id', ':user_id'));
            $qb->setParameter(':user_id', $userId->getValue());
        }

        if (!\in_array($sort, ['id', 'user_url', 'short_url', 'expires', 'created_at'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }


    public function get(string $id): Url
    {
        if (!$userUrl = $this->repository->find($id)) {
            throw new NotFoundException('User is not found');
        }
        return $userUrl;
    }


}
