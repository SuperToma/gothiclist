<?php

namespace App\Repository;

use App\Entity\VoteSong;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class VoteSongRepository
 * @package App\Repository
 */
class VoteSongRepository extends ServiceEntityRepository
{
    /**
     * SongRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VoteSong::class);
    }

    /**
     * @param $objectId
     * @return mixed
     */
    public function findVoteInfos($objectId)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(r) AS count, AVG(r.value) AS avg')
            ->andWhere('r.objectId = :objectId')
            ->setParameter('objectId', $objectId)
            ->getQuery();

        return $qb->execute()[0];
    }

}