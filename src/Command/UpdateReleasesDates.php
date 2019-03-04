<?php

namespace App\Command;

use App\Entity\Release;
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
        $releases = $this->entityManager->getRepository(Release::class)->findAll();
        foreach ($releases as $release) {
            /* @var Release $release */
            var_dump($release->getIdDiscogs());
        }
    }
}