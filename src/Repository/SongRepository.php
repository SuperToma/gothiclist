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
     * @param int $limit
     * @return mixed
     */
    public function getMostRated(int $limit = 10)
    {
        $qbd = $this->createQueryBuilder('song');
        $qbd->addSelect('COUNT(vote.id) as nbVotes')
            ->leftJoin('song.votes', 'vote')
            ->groupBy('song.id')
            ->orderBy('COUNT(vote.id)', 'DESC')
            ->setMaxResults($limit);

        $results = $qbd->getQuery()->getResult();

        foreach($results as &$result) {
            $result[0]->nbVotes = $result['nbVotes'];
            $result = $result[0];
        }

        return $results;
    }

    /**
     * @param int $styleId
     * @param int $limit
     * @return mixed
     */
    public function getLastByStyle(int $styleId, int $limit = 10)
    {
        $qbd = $this->createQueryBuilder('song');
        $qbd->innerJoin('song.release', 'release')
            ->innerJoin('release.styles', 'style')
            ->where('style.id = :styleId')->setParameter('styleId', $styleId)
            ->orderBy('song.createdAt', 'DESC')
            ->setMaxResults($limit);

        return $qbd->getQuery()->getResult();
    }

    /**
     * @param int $styleId
     * @param int $limit
     * @return mixed
     */
    public function getMostRatedByStyle(int $styleId, int $limit = 10)
    {
        $qbd = $this->createQueryBuilder('song');
        $qbd->addSelect('COUNT(vote.id) as nbVotes')
            ->innerJoin('song.release', 'release')
            ->innerJoin('release.styles', 'style')
            ->leftJoin('song.votes', 'vote')
            ->where('style.id = :styleId')->setParameter('styleId', $styleId)
            ->groupBy('song.id')
            ->orderBy('COUNT(vote.id)', 'DESC')
            ->setMaxResults($limit);

        $results = $qbd->getQuery()->getResult();

        foreach($results as &$result) {
            $result[0]->nbVotes = $result['nbVotes'];
            $result = $result[0];
        }

        return $results;
    }
}