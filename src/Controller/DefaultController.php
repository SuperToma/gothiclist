<?php

namespace App\Controller;

use App\Repository\SongRepository;
use App\Repository\VoteSongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @param SongRepository $songRepository
     * @param VoteSongRepository $voteSongRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SongRepository $songRepository, VoteSongRepository $voteSongRepository)
    {
        $lastSongs = $songRepository->findBy([], ['createdAt' => 'DESC'], 10);
        foreach($lastSongs as &$song) {
            $song->nbVotes = $voteSongRepository->count(['song' => $song]);
        }
        //dump($lastSongs[0]->getRelease()->getGenres()); exit();
        $bestSongs = '';

        return $this->render('pages/index.html.twig', [
            'last_songs' => $lastSongs,
            'best_songs' => $bestSongs
        ]);
    }
}