<?php

namespace App\Controller;

use App\Repository\SongRepository;
use App\Repository\VoteSongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @param SongRepository $songRepository
     * @param VoteSongRepository $voteSongRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SongRepository $songRepository, VoteSongRepository $voteSongRepository)
    {
        return $this->render('pages/home.html.twig', [
            'last_songs' => $songRepository->getLast(),
            'best_songs' => []
        ]);
    }
}