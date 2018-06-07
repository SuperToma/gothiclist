<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class SongRepository
 */
class SongRepository extends EntityRepository
{
    /**
     * SongRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Song::class);
    }

    public function getVotesInfos()
    {

    }

}