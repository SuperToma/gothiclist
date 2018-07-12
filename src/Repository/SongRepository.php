<?php

namespace App\Repository;

use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class SongRepository
 */
class SongRepository extends ServiceEntityRepository
{
    private $voteSongRepository;

    /**
     * SongRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry, VoteSongRepository $voteSongRepository)
    {
        parent::__construct($registry, Song::class);
        $this->voteSongRepository = $voteSongRepository;
    }

    /**
     * @param int $limit
     * @return Song[]|array
     */
    public function getLast(int $limit = 10)
    {
        $lastSongs = $this->findBy([], ['createdAt' => 'DESC'], $limit);
        foreach($lastSongs as &$song) {
            $song->nbVotes = $this->voteSongRepository->count(['song' => $song]);
        }

        return $lastSongs;
    }

    /**
     * @param int $styleId
     * @param int $limit
     * @return mixed
     */
    public function getLastByStyle(int $styleId, int $limit = 10)
    {
        $qbd = $this->createQueryBuilder('s');
        $qbd->innerJoin('s.releaseId', 'rs', 'WITH', 'rs.styleId = :styleId')
            ->setParameter('styleId', $styleId)
            ->setMaxResults($limit);

        return $qbd->getQuery()->getResult();
    }

    /**
     * @param int $styleId
     * @param int $limit
     * @return mixed
     */
    public function getMostLikedByStyle(int $styleId, int $limit = 10)
    {
        $qbd = $this->createQueryBuilder('s');
        $qbd->innerJoin('s.releaseId', 'rs', 'WITH', 'rs.styleId = :styleId')
            ->innerJoin('s.vote', 'v')
            ->groupBy('s.releaseId')
            ->setParameter('styleId', $styleId)
            ->setMaxResults($limit);

        return $qbd->getQuery()->getResult();
    }
}