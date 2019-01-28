<?php

namespace App\Repository;

use App\Entity\Style;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class StyleRepository
 * @package App\Repository
 */
class StyleRepository extends ServiceEntityRepository
{
    /**
     * StyleRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Style::class);
    }

    /**
     * @return array
     */
    public function getAllWithInfos()
    {
        $artists = $this
            ->createQueryBuilder('style')
            ->select(['COUNT(release.id)', 'style.name'])
            ->innerJoin('release.styles', 'style')
            ->groupBy('release.style')
            ->orderBy('style.name', 'ASC')
            ->getQuery()
            ->getArrayResult();

        foreach($artists as &$artist) {
            $artist['styles'] = $this->getArtistStylesById($artist['id']);
        }

        return $artists;
    }
}