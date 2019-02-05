<?php

namespace App\Repository;

use App\Entity\Release;
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
        $releaseEm = $this->getEntityManager()->getRepository(Release::class);

        return $releaseEm
            ->createQueryBuilder('release')
            ->select(['style.id', 'style.name', 'COUNT(release.id) AS nbSongs'])
            ->innerJoin('release.styles', 'style')
            ->groupBy('style.name')
            ->orderBy('COUNT(release.id)', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }
}