<?php

namespace App\Repository;

use App\Entity\VoteSong;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class VoteSongRepository
 * @package App\Repository
 */
class VoteSongRepository extends ServiceEntityRepository
{
    /**
     * SongRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoteSong::class);
    }

    /**
     * @param $objectId
     * @return mixed
     */
    public function findVoteInfos(int $objectId)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(r) AS count, AVG(r.value) AS avg')
            ->andWhere('r.objectId = :objectId')
            ->setParameter('objectId', $objectId)
            ->getQuery();

        return $qb->execute()[0];
    }

}