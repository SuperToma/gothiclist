<?php

namespace App\Repository;

use App\Entity\Artist;
use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class StyleRepository
 * @package App\Repository
 */
class ArtistRepository extends ServiceEntityRepository
{
    /**
     * StyleRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Artist::class);
    }

    /**
     * @return array
     */
    public function getAllWithInfos()
    {
        $artists = $this
            ->createQueryBuilder('artist')
            ->select(['artist.id', 'artist.name'])
            ->orderBy('artist.name', 'ASC')
            ->getQuery()
            ->getArrayResult();

        $artists = self::cleanArtistsNames($artists);

        foreach($artists as &$artist) {
            $artist['styles'] = $this->getArtistStylesById($artist['id']);
        }

        return $artists;
    }

    /**
     * @param int $id
     * @return mixed
     */
    protected function getArtistStylesById(int $id)
    {
        $songEm = $this->getEntityManager()->getRepository(Song::class);

        $qbd = $songEm->createQueryBuilder('song');
        $qbd->select(['style.id', 'style.name', 'COUNT(style.id) as nbInStyle'])
            ->innerJoin('song.release', 'release')
            ->innerJoin('release.styles', 'style')
            ->where('song.validated = :songId')
            ->andWhere('song.artist = :artistId')
            ->setParameters(['songId' => 1, 'artistId' => $id])
            ->groupBy('style.id')
            ->orderBy('COUNT(style.id)', 'DESC');

        return $qbd->getQuery()->getResult();
    }

    /**
     * @param array $artists
     * @return array
     */
    public static function cleanArtistsNames(array $artists)
    {
        foreach($artists as &$artist) {
            if(is_object($artist)) {
                $artist->setName(self::cleanArtistName($artist->getName()));
            }

            if(is_array($artist)) {
                $artist['name'] = self::cleanArtistName($artist['name']);
            }
        }

        return $artists;
    }

    /**
     * @param string $artistName
     * @return string|string[]|null
     */
    public static function cleanArtistName(string $artistName)
    {
        return preg_replace("/^(.*)( \(.*\))$/", '${1}', $artistName);
    }
}