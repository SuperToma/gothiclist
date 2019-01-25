<?php

namespace App\Repository;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class SongRepository
 * @package App\Repository
 */
class ArtistRepository extends ServiceEntityRepository
{
    /**
     * SongRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Artist::class);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $artists = $this->findBy([], ['name' => 'ASC']);

        $sql = "
        SELECT song.artist_id, style.name, COUNT(style.id) AS nbInStyle
        FROM song
        INNER JOIN `release` ON song.release_id = release.id
        INNER JOIN release_style ON `release`.id = release_style.release_id
        INNER JOIN style ON release_style.style_id = style.id
        WHERE song.validated = 1
        GROUP BY style.id
        ORDER BY nbInStyle DESC;
        ";
/*
        $qbd = $this->createQueryBuilder('song');
        $qbd->addSelect('song.artist')
            //->addSelect('style.name')
            //->addSelect('COUNT(style.id) as nbInStyle')
            ->from('App\Entity\Song', 'song')
            ->innerJoin('song.release', 'release');
           // ->innerJoin('release.id', 'release_style')
            //->innerJoin('release_style.style_id', 'style')
            //->where('song.validated = :songId')->setParameter('songId', 1)
            //->groupBy('song.id')
            //->orderBy('COUNT(style.id)', 'DESC');

        $results = $qbd->getQuery()->getResult();
        echo '<pre>'; print_r($results); exit();
        */
        return $this->cleanArtistsNames($artists);
    }

    /**
     * @param array $artists
     * @return array
     */
    protected function cleanArtistsNames(array $artists)
    {
        foreach($artists as &$artist) {
            $artist->setName(preg_replace("/^(.*)( \(.*\))$/", '${1}', $artist->getName()));
        }

        return $artists;
    }
}