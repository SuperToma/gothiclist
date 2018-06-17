<?php

namespace App\Repository;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use FOS\UserBundle\Util\Canonicalizer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserRepository
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * SongRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function emailAlreadyUsed(string $email, int $userId)
    {
        $emailCanonical = (new Canonicalizer())->canonicalize($email);
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.emailCanonical = :emailCanonical')
            ->andWhere('u.id != :userId')
            ->setParameter('emailCanonical', $emailCanonical)
            ->setParameter('userId', $userId);

        return empty($qb->getQuery()->getResult()) ? false : true;
    }

    public function nicknameAlreadyUsed(string $nickname, int $userId)
    {
        $nicknameCanonical = (new Slugify())->slugify($nickname);
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.nicknameCanonical = :nicknameCanonical')
            ->andWhere('u.id != :userId')
            ->setParameter('nicknameCanonical', $nicknameCanonical)
            ->setParameter('userId', $userId);

        return empty($qb->getQuery()->getResult()) ? false : true;
    }
}