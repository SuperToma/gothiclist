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
    public function getLast($limit = 10)
    {

        $lastSongs = $this->findBy([], ['createdAt' => 'DESC'], $limit);
        foreach($lastSongs as &$song) {
            $song->nbVotes = $this->voteSongRepository->count(['song' => $song]);
        }

        return $lastSongs;
    }
}