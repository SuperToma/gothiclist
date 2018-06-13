<?php

namespace App\Controller;

use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Song;

class DefaultController extends Controller
{
    /**
     * @param SongRepository $songRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SongRepository $songRepository)
    {
        $lastSongs = $songRepository->findBy([], ['createdAt' => 'DESC'], 10);
        return $this->render('pages/index.html.twig', [
            'last_songs' => $lastSongs,
        ]);
    }
}