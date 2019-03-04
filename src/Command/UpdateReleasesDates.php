<?php

namespace App\Command;

use App\Entity\Release;
use App\Finder\Elasticsearch\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateReleasesDates extends Command
{
    protected static $defaultName = 'app:update:releases-year';
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Update releases year, based on ES Discogs releases')
            ->setHelp('This command allows you to create a user...')
        ;
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->entityManager;
        $releases = $em->getRepository(Release::class)->findAll();
        $finder = new Finder();

        /* @var Release $release */
        foreach ($releases as $release) {

            $esRelease = $finder->getReleaseById($release->getIdDiscogs());

            if(!isset($esRelease['released'])) {
                echo 'No release date found for release '.$release->getId().': '.$release->getTitle();
                next();
            }

            $year = null;
            if(strlen($esRelease['released']) === 4) {
                $year = $esRelease['released'];
            } elseif(strlen($esRelease['released']) === 10) {
                $year = substr($esRelease['released'], 0, 4);
            }

            $release->setYear($year);
            $em->persist($release);
            $em->flush();
        }



    }
}