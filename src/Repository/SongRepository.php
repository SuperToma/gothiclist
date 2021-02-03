<?php

namespace App\Repository;

use App\Entity\Artist;
use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class SongRepository
 * @package App\Repository
 */
class SongRepository extends ServiceEntityRepository
{
    private $voteSongRepository;

    /**
     * SongRepository constructor.
     * @param ManagerRegistry $registry
     * @param VoteSongRepository $voteSongRepository
     */
    public function __construct(ManagerRegistry $registry, VoteSongRepository $voteSongRepository)
    {
        parent::__construct($registry, Song::class);
        $this->voteSongRepository = $voteSongRepository;
    }

    /**
     * @param Artist $artist
     * @param Song|null $excludedSong
     * @return mixed
     */
    public function getByArtist(Artist $artist, Song $excludedSong = null)
    {
        $qbd = $this->createQueryBuilder('song');
        $qbd->where('song.artist = :artistId')
            ->andWhere('song.id != :songId')
            ->andWhere('song.validated = :validated')
            ->setParameter('artistId', $artist->getId())
            ->setParameter('songId', $excludedSong ? $excludedSong->getId() : null)
            ->setParameter('validated', 1);

        $songs = $qbd->getQuery()->getResult();
        foreach ($songs as &$song) {
            $song->nbVotes = $this->voteSongRepository->count(['song' => $song]);
        }

        return $songs;
    }

    /**
     * @param int $limit
     * @param array $criteria
     * @param bool $withCountVotes
     * @return array
     */
    public function getLast(int $limit = 10, array $criteria = [], $withCountVotes = true)
    {
        $lastSongs = $this->findBy($criteria, ['createdAt' => 'DESC'], $limit);
        foreach ($lastSongs as &$song) {
            $song->getArtist()->setName(ArtistRepository::cleanArtistName($song->getArtist()->getName()));
        }

        if($withCountVotes) {
            foreach ($lastSongs as &$song) {
                $song->nbVotes = $this->voteSongRepository->count(['song' => $song]);
            }
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
            $result[0]->getArtist()->setName(ArtistRepository::cleanArtistName($result[0]->getArtist()->getName()));
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