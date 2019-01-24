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