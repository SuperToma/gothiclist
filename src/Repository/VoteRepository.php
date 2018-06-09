<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class VoteRepository
 * @package App\Repository
 */
class VoteRepository extends ServiceEntityRepository
{
    /**
     * SongRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    /**
     * @param $type
     * @param $objectId
     * @return mixed
     */
    public function findVoteInfos($type, $objectId)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(r) AS count, AVG(r.value) AS avg')
            ->where('r.type = :type')
            ->andWhere('r.objectId = :objectId')
            ->setParameter('type', $type)
            ->setParameter('objectId', $objectId)
            ->getQuery();

        return $qb->execute()[0];
    }

}