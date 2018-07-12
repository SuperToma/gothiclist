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
        $lastSongsAdded = $songRepository->getLast();
        foreach($lastSongsAdded as &$song) {
            $song->nbVotes = $voteSongRepository->count(['song' => $song]);
        }

        return $this->render('pages/home.html.twig', [
            'last_songs' => $lastSongsAdded,
            'most_rated_songs' => $songRepository->getMostRated(),
        ]);
    }
}