<?php

declare(strict_types=1);

namespace App\ReadModel\User\Url\Log;

use App\Model\User\Entity\User\Url;
use App\Model\User\Entity\User\UserId;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UrlFetcher
 * @package App\ReadModel\User\Url
 */
class UrlLogFetcher
{
    private $connection;
    private $paginator;
    private $repository;

    public function __construct(Connection $connection, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(Url\Log::class);
        $this->paginator = $paginator;
    }

    /**
     * @param UserId|null $userId
     * @param null $userUrlId
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all($userId = null, $userUrlId = null, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'user_url_log.id',
                'user_url_log.ip',
                'user_url_log.user_url_id',
                'user_url_log.browser',
                'user_url_log.country',
                'user_url_log.created_at'
            )
            ->from('user_url_log')
            ->leftJoin('user_url_log', 'user_url', 'uu', 'user_url_log.user_url_id = uu.id');
        //pass user_id to the where clause(if defined)

        if (null !== $userId) {
            $qb->andWhere('uu.user_id = :user_id');
            $qb->setParameter(':user_id', $userId);
        }
        if (null !== $userUrlId) {
            $qb->andWhere('user_url_log.user_url_id = :user_url_id');
            $qb->setParameter(':user_url_id', $userUrlId);
        }
        if (!\in_array($sort, ['id', 'ip', 'browser', 'country', 'created_at'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function get(string $id): Url\Log
    {
        if (!$userUrlLog = $this->repository->find($id)) {
            throw new NotFoundException('User is not found');
        }
        return $userUrlLog;
    }

}